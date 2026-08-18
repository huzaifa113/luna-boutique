<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    public function __construct(
        protected UnitConversionService $unitConversion,
        protected StockService $stockService,
    ) {}

    /**
     * Create a draft purchase.
     */
    public function createDraft(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            $purchase = Purchase::create([
                'vendor_id' => $data['vendor_id'],
                'invoice_number' => $this->generateInvoiceNumber(),
                'vendor_invoice_no' => $data['vendor_invoice_no'] ?? null,
                'purchase_date' => $data['purchase_date'],
                'status' => Purchase::STATUS_DRAFT,
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'freight' => $data['freight'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            if (! empty($data['items'])) {
                $this->syncItems($purchase, $data['items']);
            }

            $this->recalculateTotals($purchase);

            return $purchase;
        });
    }

    /**
     * Update a draft purchase.
     */
    public function updateDraft(Purchase $purchase, array $data): Purchase
    {
        if ($purchase->status !== Purchase::STATUS_DRAFT) {
            throw ValidationException::withMessages(['purchase' => 'Only draft purchases can be updated.']);
        }

        return DB::transaction(function () use ($purchase, $data) {
            $purchase->update([
                'vendor_id' => $data['vendor_id'],
                'vendor_invoice_no' => $data['vendor_invoice_no'] ?? $purchase->vendor_invoice_no,
                'purchase_date' => $data['purchase_date'],
                'discount' => $data['discount'] ?? $purchase->discount,
                'tax' => $data['tax'] ?? $purchase->tax,
                'freight' => $data['freight'] ?? $purchase->freight,
                'notes' => $data['notes'] ?? $purchase->notes,
            ]);

            if (isset($data['items'])) {
                $purchase->items()->delete();
                $this->syncItems($purchase, $data['items']);
            }

            return $this->recalculateTotals($purchase);
        });
    }

    /**
     * Confirm a purchase - increase stock, update cost prices.
     */
    public function confirm(Purchase $purchase): Purchase
    {
        if ($purchase->status !== Purchase::STATUS_DRAFT) {
            throw ValidationException::withMessages(['purchase' => 'Only draft purchases can be confirmed.']);
        }

        return DB::transaction(function () use ($purchase) {
            $items = $purchase->items()->with('product')->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'Purchase must have at least one item.']);
            }

            foreach ($items as $item) {
                // Increase stock with received quantity
                $this->stockService->increase(
                    $item->product,
                    (float) $item->received_base_quantity,
                    StockMovement::REASON_PURCHASE,
                    $purchase,
                    (float) $item->base_unit_rate
                );

                // Update cost_price using weighted average
                $this->updateWeightedAverageCost($item->product, (float) $item->received_base_quantity, (float) $item->base_unit_rate);

                // Update product_units.purchase_rate for the unit used
                $item->product->productUnits()
                    ->where('unit_id', $item->unit_id)
                    ->update(['purchase_rate' => (float) $item->rate]);
            }

            $this->recalculateTotals($purchase);
            $purchase->update(['status' => Purchase::STATUS_CONFIRMED]);

            return $purchase->fresh();
        });
    }

    /**
     * Cancel a purchase - reverse stock.
     */
    public function cancel(Purchase $purchase): Purchase
    {
        if ($purchase->vendorPayments()->exists()) {
            throw ValidationException::withMessages(['purchase' => 'Cannot cancel a purchase that has payments.']);
        }

        return DB::transaction(function () use ($purchase) {
            if ($purchase->status === Purchase::STATUS_CONFIRMED) {
                foreach ($purchase->items as $item) {
                    $this->stockService->decrease(
                        $item->product,
                        (float) $item->received_base_quantity,
                        StockMovement::REASON_PURCHASE_CANCEL,
                        $purchase
                    );
                }
            }

            $purchase->update(['status' => Purchase::STATUS_CANCELLED]);

            return $purchase->fresh();
        });
    }

    /**
     * Recalculate purchase totals from items.
     */
    public function recalculateTotals(Purchase $purchase): Purchase
    {
        $items = $purchase->items()->get();

        $subtotal = round((float) $items->sum('gross_amount'), 2);
        $shortageAdjustment = round((float) $items->sum('shortage_amount'), 2);
        $discount = (float) $purchase->discount;
        $tax = (float) $purchase->tax;
        $freight = (float) $purchase->freight;

        $total = round($subtotal - $shortageAdjustment - $discount + $tax + $freight, 2);

        $purchase->update([
            'subtotal' => $subtotal,
            'shortage_adjustment' => $shortageAdjustment,
            'total' => $total,
        ]);

        return $purchase->fresh();
    }

    /**
     * Sync purchase items (create from array data).
     */
    protected function syncItems(Purchase $purchase, array $items): void
    {
        foreach ($items as $line) {
            $product = Product::findOrFail($line['product_id']);
            $quantity = (float) ($line['quantity'] ?? 0);
            $rate = (float) ($line['rate'] ?? 0);
            $factor = $this->unitConversion->resolveFactor($product, (int) $line['unit_id'], $line['factor'] ?? null);
            $shortageQuantity = (float) ($line['shortage_quantity'] ?? 0);

            if ($quantity <= 0) {
                throw ValidationException::withMessages(['items' => "Quantity must be greater than 0 for {$product->name}."]);
            }

            if ($rate < 0) {
                throw ValidationException::withMessages(['items' => "Rate must be 0 or greater for {$product->name}."]);
            }

            $grossBaseQty = $this->unitConversion->toBaseQuantity($quantity, $factor);

            if ($shortageQuantity < 0 || $shortageQuantity > $grossBaseQty) {
                throw ValidationException::withMessages(['items' => "Shortage must be between 0 and {$grossBaseQty} for {$product->name}."]);
            }

            $grossAmount = round($quantity * $rate, 2);
            $baseUnitRate = $this->unitConversion->baseUnitRate($grossAmount, $grossBaseQty);
            $receivedBaseQty = $grossBaseQty - $shortageQuantity;
            $shortageAmount = round($shortageQuantity * $baseUnitRate, 2);
            $netAmount = $grossAmount - $shortageAmount;

            $unit = Unit::findOrFail($line['unit_id']);

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'factor' => $factor,
                'quantity' => $quantity,
                'gross_base_quantity' => $grossBaseQty,
                'shortage_quantity' => $shortageQuantity,
                'received_base_quantity' => $receivedBaseQty,
                'rate' => $rate,
                'base_unit_rate' => $baseUnitRate,
                'gross_amount' => $grossAmount,
                'shortage_amount' => $shortageAmount,
                'net_amount' => $netAmount,
                'notes' => $line['notes'] ?? null,
            ]);
        }
    }

    /**
     * Update product cost_price using weighted average.
     */
    protected function updateWeightedAverageCost(Product $product, float $incomingQty, float $incomingRate): void
    {
        $currentStock = (float) $product->stock_quantity;
        $currentQty = $currentStock - $incomingQty; // what we had before this purchase
        $currentCost = (float) $product->cost_price;

        $totalValue = ($currentQty * $currentCost) + ($incomingQty * $incomingRate);
        $totalQty = $currentQty + $incomingQty;

        if ($totalQty > 0) {
            $newCost = round($totalValue / $totalQty, 2);
            $product->update(['cost_price' => $newCost]);
        }
    }

    /**
     * Generate the next purchase invoice number.
     */
    protected function generateInvoiceNumber(): string
    {
        $prefix = config('pos.invoice.purchase_prefix', 'PUR-');
        $padding = (int) config('pos.invoice.number_padding', 5);

        $last = Purchase::where('invoice_number', 'like', $prefix.'%')
            ->orderBy('id', 'desc')
            ->value('invoice_number');

        if ($last) {
            $num = (int) substr($last, strlen($prefix)) + 1;
        } else {
            $num = 1;
        }

        return $prefix.str_pad((string) $num, $padding, '0', STR_PAD_LEFT);
    }
}
