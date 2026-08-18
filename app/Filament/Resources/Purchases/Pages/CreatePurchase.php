<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\VendorPayment;
use App\Services\StockService;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate invoice number
        $lastPurchase = Purchase::query()
            ->where('invoice_number', 'like', 'PUR-%')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        if ($lastPurchase && preg_match('/PUR-(\d+)$/', $lastPurchase->invoice_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = Purchase::query()->count() + 1;
        }

        $data['invoice_number'] = 'PUR-' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);

        // Purchases are created as confirmed so they appear in vendor payments
        // and are immediately reflected in stock/accounting.
        $data['status'] = Purchase::STATUS_CONFIRMED;

        // Calculate purchase-level totals from items
        $subtotal = 0;
        foreach ($data['items'] ?? [] as $item) {
            $subtotal += (float) ($item['gross_amount'] ?? 0);
        }

        $data['subtotal'] = round($subtotal, 2);

        $discount = (float) ($data['discount'] ?? 0);
        $tax = (float) ($data['tax'] ?? 0);
        $freight = (float) ($data['freight'] ?? 0);
        $data['total'] = round($data['subtotal'] - $discount + $tax + $freight, 2);

        // Remove items from data - we'll save them manually in afterCreate
        $this->cachedItems = $data['items'] ?? [];
        unset($data['items']);

        // Cache payment data - we'll create the payment in afterCreate
        $this->cachedPayment = [
            'make_payment' => $data['make_payment'] ?? false,
            'payment_date' => $data['payment_date'] ?? null,
            'payment_amount' => $data['payment_amount'] ?? null,
            'payment_method' => $data['payment_method'] ?? null,
            'payment_reference_no' => $data['payment_reference_no'] ?? null,
            'payment_notes' => $data['payment_notes'] ?? null,
        ];

        // Remove payment fields - they don't belong on the purchases table
        unset(
            $data['make_payment'],
            $data['payment_date'],
            $data['payment_amount'],
            $data['payment_method'],
            $data['payment_reference_no'],
            $data['payment_notes'],
        );

        return $data;
    }

    protected array $cachedItems = [];

    protected array $cachedPayment = [];

    protected function afterCreate(): void
    {
        $purchase = $this->record;
        $stockService = app(StockService::class);

        foreach ($this->cachedItems as $itemData) {
            $productId = (int) ($itemData['product_id'] ?? 0);
            $unitId = (int) ($itemData['unit_id'] ?? 0);
            $quantity = (float) ($itemData['quantity'] ?? 0);
            $rate = (float) ($itemData['rate'] ?? 0);
            $factor = (float) ($itemData['factor'] ?? 1);
            $shortageQty = (float) ($itemData['shortage_quantity'] ?? 0);
            $notes = $itemData['notes'] ?? '';

            $product = Product::find($productId);
            $unit = Unit::find($unitId);

            $grossAmount = round($quantity * $rate, 2);
            $grossBaseQty = round($quantity * $factor, 3);
            $baseUnitRate = $factor > 0 ? round($rate / $factor, 4) : 0;
            $shortageAmount = round($shortageQty * $baseUnitRate, 2);
            $netAmount = round($grossAmount - $shortageAmount, 2);
            $receivedBaseQty = round($grossBaseQty - $shortageQty, 3);

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $productId,
                'product_name' => $product ? $product->name : '',
                'product_sku' => $product ? $product->sku : '',
                'unit_id' => $unitId,
                'unit_name' => $unit ? $unit->name : '',
                'factor' => $factor,
                'quantity' => $quantity,
                'gross_base_quantity' => $grossBaseQty,
                'shortage_quantity' => $shortageQty,
                'received_base_quantity' => $receivedBaseQty,
                'rate' => $rate,
                'base_unit_rate' => $baseUnitRate,
                'gross_amount' => $grossAmount,
                'shortage_amount' => $shortageAmount,
                'net_amount' => $netAmount,
                'notes' => $notes,
            ]);

            // Since the purchase is created as confirmed, increase stock
            // and update cost prices to stay consistent with PurchaseService::confirm().
            if ($product) {
                $stockService->increase(
                    $product,
                    $receivedBaseQty,
                    StockMovement::REASON_PURCHASE,
                    $purchase,
                    $baseUnitRate
                );

                $this->updateWeightedAverageCost($product, $receivedBaseQty, $baseUnitRate);

                $product->productUnits()
                    ->where('unit_id', $unitId)
                    ->update(['purchase_rate' => $rate]);
            }
        }

        // Create a vendor payment if the user opted to make one
        if ($this->cachedPayment['make_payment'] ?? false) {
            VendorPayment::create([
                'vendor_id' => $purchase->vendor_id,
                'purchase_id' => $purchase->id,
                'payment_date' => $this->cachedPayment['payment_date'] ?? now(),
                'amount' => $this->cachedPayment['payment_amount'] ?? 0,
                'method' => $this->cachedPayment['payment_method'] ?? VendorPayment::METHOD_CASH,
                'reference_no' => $this->cachedPayment['payment_reference_no'] ?? null,
                'notes' => $this->cachedPayment['payment_notes'] ?? null,
                'user_id' => auth()->id(),
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
}