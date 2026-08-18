<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(
        protected UnitConversionService $unitConversion,
        protected StockService $stockService,
    ) {}

    /**
     * Create a draft sale.
     */
    public function createDraft(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $sale = Sale::create([
                'customer_id' => $data['customer_id'] ?? null,
                'walk_in_name' => $data['walk_in_name'] ?? null,
                'walk_in_phone' => $data['walk_in_phone'] ?? null,
                'invoice_number' => $this->generateInvoiceNumber(),
                'sale_date' => $data['sale_date'],
                'status' => Sale::STATUS_DRAFT,
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'delivery_charges' => $data['delivery_charges'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            if (! empty($data['items'])) {
                $this->syncItems($sale, $data['items']);
            }

            $this->recalculateTotals($sale);

            return $sale;
        });
    }

    /**
     * Update a draft sale.
     */
    public function updateDraft(Sale $sale, array $data): Sale
    {
        if ($sale->status !== Sale::STATUS_DRAFT) {
            throw ValidationException::withMessages(['sale' => 'Only draft sales can be updated.']);
        }

        return DB::transaction(function () use ($sale, $data) {
            $sale->update([
                'customer_id' => $data['customer_id'] ?? null,
                'walk_in_name' => $data['walk_in_name'] ?? null,
                'walk_in_phone' => $data['walk_in_phone'] ?? null,
                'sale_date' => $data['sale_date'],
                'discount' => $data['discount'] ?? $sale->discount,
                'tax' => $data['tax'] ?? $sale->tax,
                'delivery_charges' => $data['delivery_charges'] ?? $sale->delivery_charges,
                'notes' => $data['notes'] ?? $sale->notes,
            ]);

            if (isset($data['items'])) {
                $sale->items()->delete();
                $this->syncItems($sale, $data['items']);
            }

            return $this->recalculateTotals($sale);
        });
    }

    /**
     * Complete a sale - decrease stock.
     */
    public function complete(Sale $sale): Sale
    {
        if ($sale->status !== Sale::STATUS_DRAFT) {
            throw ValidationException::withMessages(['sale' => 'Only draft sales can be completed.']);
        }

        return DB::transaction(function () use ($sale) {
            $items = $sale->items()->with('product')->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'Sale must have at least one item.']);
            }

            // Pre-check stock availability for ALL lines before mutating
            foreach ($items as $item) {
                $this->stockService->assertAvailable($item->product, (float) $item->gross_base_quantity);
            }

            $totalShortageCost = 0;

            foreach ($items as $item) {
                $product = $item->product;

                // Decrease stock for billed (delivered) quantity
                $this->stockService->decrease(
                    $product,
                    (float) $item->billed_base_quantity,
                    StockMovement::REASON_SALE,
                    $sale,
                    (float) $item->base_unit_cost
                );

                // If shortage, record a separate sale_shortage movement
                if ((float) $item->shortage_quantity > 0) {
                    $this->stockService->decrease(
                        $product,
                        (float) $item->shortage_quantity,
                        StockMovement::REASON_SALE_SHORTAGE,
                        $sale,
                        (float) $item->base_unit_cost
                    );
                }

                // Accumulate shortage cost at cost price
                $totalShortageCost += (float) $item->shortage_quantity * (float) $item->base_unit_cost;
            }

            $this->recalculateTotals($sale);

            $sale->update([
                'status' => Sale::STATUS_COMPLETED,
                'shortage_cost' => round($totalShortageCost, 2),
            ]);

            $sale->refreshPaymentStatus();

            return $sale->fresh();
        });
    }

    /**
     * Cancel a sale - restore stock.
     */
    public function cancel(Sale $sale): Sale
    {
        if ($sale->customerPayments()->exists()) {
            throw ValidationException::withMessages(['sale' => 'Cannot cancel a sale that has payments.']);
        }

        return DB::transaction(function () use ($sale) {
            if ($sale->status === Sale::STATUS_COMPLETED) {
                foreach ($sale->items as $item) {
                    // Restore total gross quantity (billed + shortage)
                    $this->stockService->increase(
                        $item->product,
                        (float) $item->gross_base_quantity,
                        StockMovement::REASON_SALE_CANCEL,
                        $sale
                    );
                }
            }

            $sale->update(['status' => Sale::STATUS_CANCELLED]);

            return $sale->fresh();
        });
    }

    /**
     * Recalculate sale totals from items.
     */
    public function recalculateTotals(Sale $sale): Sale
    {
        $items = $sale->items()->get();

        $subtotal = round((float) $items->sum('gross_amount'), 2);
        $shortageAdjustment = round((float) $items->sum('shortage_amount'), 2);
        $discount = (float) $sale->discount;
        $tax = (float) $sale->tax;
        $deliveryCharges = (float) $sale->delivery_charges;

        $total = round($subtotal - $shortageAdjustment - $discount + $tax + $deliveryCharges, 2);

        $sale->update([
            'subtotal' => $subtotal,
            'shortage_adjustment' => $shortageAdjustment,
            'total' => $total,
        ]);

        return $sale->fresh();
    }

    /**
     * Refresh payment status.
     */
    public function refreshPaymentStatus(Sale $sale): Sale
    {
        return $sale->refreshPaymentStatus();
    }

    /**
     * Sync sale items (create from array data).
     */
    protected function syncItems(Sale $sale, array $items): void
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
            $billedBaseQty = $grossBaseQty - $shortageQuantity;
            $shortageAmount = round($shortageQuantity * $baseUnitRate, 2);
            $netAmount = $grossAmount - $shortageAmount;
            $baseUnitCost = (float) $product->cost_price;

            $unit = Unit::findOrFail($line['unit_id']);

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'factor' => $factor,
                'quantity' => $quantity,
                'gross_base_quantity' => $grossBaseQty,
                'shortage_quantity' => $shortageQuantity,
                'billed_base_quantity' => $billedBaseQty,
                'rate' => $rate,
                'base_unit_rate' => $baseUnitRate,
                'base_unit_cost' => $baseUnitCost,
                'gross_amount' => $grossAmount,
                'shortage_amount' => $shortageAmount,
                'net_amount' => $netAmount,
                'notes' => $line['notes'] ?? null,
            ]);
        }
    }

    /**
     * Generate the next sale invoice number.
     */
    protected function generateInvoiceNumber(): string
    {
        $prefix = config('pos.invoice.sale_prefix', 'INV-');
        $padding = (int) config('pos.invoice.number_padding', 5);

        $last = Sale::where('invoice_number', 'like', $prefix.'%')
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
