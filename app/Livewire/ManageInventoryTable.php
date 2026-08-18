<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use App\Services\InventoryService;
use Livewire\Component;

class ManageInventoryTable extends Component
{
    public array $products = [];

    // Unit editing state
    public bool $showUnitModal = false;
    public ?int $editingProductId = null;
    public string $editingProductName = '';
    public array $unitRows = [];

    public function mount(): void
    {
        $this->loadProducts();
    }

    protected function loadProducts(): void
    {
        $products = Product::with(['baseUnit', 'productUnits.unit'])
            ->orderBy('name')
            ->get();

        $this->products = $products->map(function (Product $product) {
            $inventoryService = app(InventoryService::class);
            $units = $product->productUnits()
                ->with(['unit', 'children.unit'])
                ->whereNull('parent_product_unit_id')
                ->get();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'base_unit' => $product->baseUnit?->name ?? 'N/A',
                'stock_quantity' => (float) $product->stock_quantity,
                'cost_price' => (float) ($product->cost_price ?? 0),
                'price' => (float) ($product->price ?? 0),
                'track_stock' => $product->track_stock,
                'low_stock_threshold' => (float) ($product->low_stock_threshold ?? 0),
                'units_count' => $product->productUnits->count(),
                'hierarchy' => $units->map(function (ProductUnit $pu) use ($inventoryService) {
                    return $this->buildUnitNode($pu, $inventoryService);
                })->toArray(),
            ];
        })->toArray();
    }

    protected function buildUnitNode(ProductUnit $productUnit, InventoryService $inventoryService): array
    {
        $landedCost = $inventoryService->getUnitLandedCost($productUnit);
        $stockInBase = (float) $productUnit->product->stock_quantity;
        $totalBaseUnits = $productUnit->total_base_units;
        $stockAtThisUnit = $totalBaseUnits > 0 ? round($stockInBase / $totalBaseUnits, 3) : 0;
        $children = $productUnit->children()->with(['unit', 'children.unit'])->get();

        $node = [
            'id' => $productUnit->id,
            'unit_id' => $productUnit->unit_id,
            'unit_name' => $productUnit->unit?->name ?? 'N/A',
            'barcode' => $productUnit->barcode,
            'contains_quantity' => (float) ($productUnit->contains_quantity ?? 0),
            'sale_rate' => (float) ($productUnit->sale_rate ?? 0),
            'purchase_rate' => (float) ($productUnit->purchase_rate ?? 0),
            'factor' => (float) $productUnit->factor,
            'parent_product_unit_id' => $productUnit->parent_product_unit_id,
            'landed_cost' => $landedCost,
            'stock' => $stockAtThisUnit,
            'margin' => $landedCost > 0 && $productUnit->sale_rate > 0
                ? round((($productUnit->sale_rate - $landedCost) / $productUnit->sale_rate) * 100, 1)
                : 0,
            'children' => $children->map(function ($child) use ($inventoryService) {
                return $this->buildUnitNode($child, $inventoryService);
            })->toArray(),
        ];

        return $node;
    }

    /**
     * Open the inline unit editor for a product.
     */
    public function editUnits(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        $this->editingProductId = $productId;
        $this->editingProductName = $product->name;
        $this->loadUnitRows($product);
        $this->showUnitModal = true;
    }

    protected function loadUnitRows(Product $product): void
    {
        $units = $product->productUnits()
            ->with(['unit', 'parent'])
            ->get();

        $this->unitRows = $units->map(function (ProductUnit $pu) {
            return [
                'id' => $pu->id,
                'unit_id' => $pu->unit_id,
                'unit_name' => $pu->unit?->name ?? '',
                'parent_product_unit_id' => $pu->parent_product_unit_id,
                'contains_quantity' => (float) ($pu->contains_quantity ?? 0),
                'barcode' => $pu->barcode,
                'sale_rate' => (float) ($pu->sale_rate ?? 0),
                'purchase_rate' => (float) ($pu->purchase_rate ?? 0),
                'factor' => (float) $pu->factor,
            ];
        })->toArray();
    }

    /**
     * Available units list for the inline editor (both select + parent select).
     */
    public function getUnitOptions(): array
    {
        return Unit::where('is_active', true)->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function getParentUnitOptions(): array
    {
        $options = [];
        foreach ($this->unitRows as $row) {
            if (empty($row['id'])) {
                continue;
            }
            $options[$row['id']] = $row['unit_name'];
        }

        return $options;
    }

    public function addUnitRow(): void
    {
        $this->unitRows[] = [
            'id' => null,
            'unit_id' => null,
            'unit_name' => '',
            'parent_product_unit_id' => null,
            'contains_quantity' => 0,
            'barcode' => null,
            'sale_rate' => 0,
            'purchase_rate' => 0,
            'factor' => 1,
        ];
    }

    public function removeUnitRow(int $index): void
    {
        $row = $this->unitRows[$index] ?? null;
        if (! $row) {
            return;
        }

        // If it's an existing unit, delete it (and its children get orphaned to top-level)
        if (! empty($row['id'])) {
            ProductUnit::where('id', $row['id'])->delete();
        }

        unset($this->unitRows[$index]);
        $this->unitRows = array_values($this->unitRows);
    }

    public function saveUnits(): void
    {
        if (! $this->editingProductId) {
            return;
        }

        foreach ($this->unitRows as $row) {
            $unitId = $row['unit_id'] ?? null;
            if (! $unitId) {
                continue;
            }

            $data = [
                'unit_id' => $unitId,
                'parent_product_unit_id' => ! empty($row['parent_product_unit_id']) ? $row['parent_product_unit_id'] : null,
                'contains_quantity' => ! empty($row['contains_quantity']) ? (float) $row['contains_quantity'] : null,
                'barcode' => $row['barcode'] ?? null,
                'sale_rate' => ! empty($row['sale_rate']) ? (float) $row['sale_rate'] : null,
                'purchase_rate' => ! empty($row['purchase_rate']) ? (float) $row['purchase_rate'] : null,
                'factor' => ! empty($row['factor']) ? (float) $row['factor'] : 1,
            ];

            if (! empty($row['id'])) {
                $productUnit = ProductUnit::find($row['id']);
                if ($productUnit) {
                    $productUnit->update($data);
                }
            } else {
                ProductUnit::create(array_merge($data, [
                    'product_id' => $this->editingProductId,
                    'is_base' => false,
                    'is_default_purchase' => false,
                    'is_default_sale' => false,
                ]));
            }
        }

        session()->flash('unit_saved', 'Product units saved successfully.');

        $this->showUnitModal = false;
        $this->editingProductId = null;
        $this->editingProductName = '';
        $this->unitRows = [];
        $this->loadProducts();
    }

    public function cancelEdit(): void
    {
        $this->showUnitModal = false;
        $this->editingProductId = null;
        $this->editingProductName = '';
        $this->unitRows = [];
    }

    public function render()
    {
        return view('livewire.manage-inventory-table');
    }
}