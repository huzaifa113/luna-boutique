<?php

namespace App\Models;

use Database\Factories\ProductUnitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['product_id', 'unit_id', 'factor', 'is_base', 'is_default_purchase', 'is_default_sale', 'purchase_rate', 'sale_rate', 'barcode', 'parent_product_unit_id', 'contains_quantity'])]
class ProductUnit extends Model
{
    /** @use HasFactory<ProductUnitFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_base' => 'boolean',
            'is_default_purchase' => 'boolean',
            'is_default_sale' => 'boolean',
            'factor' => 'decimal:4',
            'contains_quantity' => 'decimal:4',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_product_unit_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_product_unit_id');
    }

    /**
     * Get all descendants (recursive children).
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the root ancestor (top-most parent).
     */
    public function rootAncestor(): self
    {
        $ancestor = $this;
        while ($ancestor->parent) {
            $ancestor = $ancestor->parent;
        }

        return $ancestor;
    }

    /**
     * Check if this is a leaf unit (has no children).
     */
    public function isLeaf(): bool
    {
        return ! $this->children()->exists();
    }

    /**
     * Get the depth level in the hierarchy (0 = root).
     */
    public function getLevelAttribute(): int
    {
        $level = 0;
        $current = $this;
        while ($current->parent) {
            $level++;
            $current = $current->parent;
        }

        return $level;
    }

    /**
     * Calculate how many base units this unit represents.
     *
     * Walks down the hierarchy from this node to the leaf, multiplying
     * contains_quantity at each level.
     *
     * Example: Carton (contains 5 Boxes) → Box (contains 10 kg) → kg (leaf)
     *   - kg returns 1
     *   - Box returns 10
     *   - Carton returns 5 * 10 = 50
     */
    public function getTotalBaseUnitsAttribute(): float
    {
        $total = 1.0;
        $current = $this;

        while ($current->contains_quantity && $current->children()->exists()) {
            $total *= (float) $current->contains_quantity;
            $current = $current->children()->first();
        }

        return $total;
    }
}
