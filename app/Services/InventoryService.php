<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Collection;

class InventoryService
{
    /**
     * Get the hierarchy tree for a product's units.
     * Returns root-level ProductUnits with their children loaded recursively.
     *
     * @return Collection<int, ProductUnit>
     */
    public function getProductHierarchy(Product $product): Collection
    {
        return $product->productUnits()
            ->whereNull('parent_product_unit_id')
            ->with(['unit', 'children.unit', 'children.children.unit'])
            ->get();
    }

    /**
     * Calculate landed cost per base unit for a purchase item.
     * Apportions purchase-level overheads (freight, tax, discount) proportionally.
     */
    public function calculateItemLandedCost(PurchaseItem $item): float
    {
        $purchase = $item->purchase;
        if (! $purchase) {
            return (float) $item->base_unit_rate;
        }

        $allItems = $purchase->items;
        $purchaseSubtotal = (float) $allItems->sum('gross_amount');

        if ($purchaseSubtotal <= 0) {
            return (float) $item->base_unit_rate;
        }

        // Calculate this item's proportion of the purchase subtotal
        $itemSubtotal = (float) $item->gross_amount;
        $itemProportion = $itemSubtotal / $purchaseSubtotal;

        // Apportion overheads (freight + tax - discount)
        $overheads = (float) $purchase->freight + (float) $purchase->tax - (float) $purchase->discount;
        $itemOverheadShare = $overheads * $itemProportion;

        // Landed cost for this item = net_amount + proportional overheads
        $itemLandedCost = (float) $item->net_amount + $itemOverheadShare;

        // Per base unit
        $receivedBaseQty = (float) $item->received_base_quantity;
        if ($receivedBaseQty <= 0) {
            return 0;
        }

        return round($itemLandedCost / $receivedBaseQty, 4);
    }

    /**
     * Get the average landed cost per base unit for a product
     * across all confirmed purchases.
     */
    public function getAverageLandedCost(Product $product): float
    {
        $items = PurchaseItem::where('product_id', $product->id)
            ->whereHas('purchase', function ($q) {
                $q->where('status', Purchase::STATUS_CONFIRMED);
            })
            ->with('purchase')
            ->get();

        if ($items->isEmpty()) {
            return (float) $product->cost_price;
        }

        $totalCost = 0;
        $totalQty = 0;

        foreach ($items as $item) {
            $landedCostPerUnit = $this->calculateItemLandedCost($item);
            $receivedQty = (float) $item->received_base_quantity;
            $totalCost += $landedCostPerUnit * $receivedQty;
            $totalQty += $receivedQty;
        }

        if ($totalQty <= 0) {
            return (float) $product->cost_price;
        }

        return round($totalCost / $totalQty, 4);
    }

    /**
     * Get the landed cost for a specific ProductUnit level.
     * Walks up the hierarchy to calculate the cost at this unit level.
     */
    public function getUnitLandedCost(ProductUnit $productUnit): float
    {
        $baseLandedCost = $this->getAverageLandedCost($productUnit->product);

        // Multiply by the number of base units this unit represents
        return round($baseLandedCost * $productUnit->total_base_units, 2);
    }

    /**
     * Build a complete inventory tree for all products that have bag/carton units.
     *
     * @return Collection<int, array{product: Product, units: Collection}>
     */
    public function getInventoryTree(): Collection
    {
        $products = Product::whereHas('productUnits', function ($q) {
            $q->whereNotNull('parent_product_unit_id')
                ->orWhere(function ($q2) {
                    $q2->whereHas('unit', function ($q3) {
                        $q3->whereIn('name', ['bag', 'carton']);
                    });
                });
        })->with(['productUnits' => function ($q) {
            $q->whereNull('parent_product_unit_id')
                ->with(['unit', 'children.unit', 'children.children.unit']);
        }])->get();

        return $products->map(function ($product) {
            return [
                'product' => $product,
                'units' => $product->productUnits,
            ];
        });
    }
}