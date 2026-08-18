<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\Unit;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

        // Remove items from data - we'll save them manually in afterSave
        $this->cachedItems = $data['items'] ?? [];
        unset($data['items']);

        return $data;
    }

    protected array $cachedItems = [];

    protected function afterSave(): void
    {
        $purchase = $this->record;

        // Delete existing items
        $purchase->items()->delete();

        // Create new items from cached data
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
        }
    }
}