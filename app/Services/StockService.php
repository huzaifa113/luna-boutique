<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    /**
     * Increase stock for a product.
     *
     * @throws ValidationException
     */
    public function increase(Product $product, float $baseQuantity, string $reason, ?Model $reference = null, ?float $unitCost = null, ?string $notes = null): StockMovement
    {
        return DB::transaction(function () use ($product, $baseQuantity, $reason, $reference, $unitCost, $notes) {
            $product = $product->lockForUpdate()->findOrFail($product->id);
            $balanceAfter = (float) $product->stock_quantity + $baseQuantity;

            if ($product->track_stock) {
                $product->update(['stock_quantity' => $balanceAfter]);
            }

            return $this->recordMovement($product, StockMovement::TYPE_IN, $baseQuantity, $reason, $balanceAfter, $reference, $unitCost, $notes);
        });
    }

    /**
     * Decrease stock for a product.
     *
     * @throws ValidationException
     */
    public function decrease(Product $product, float $baseQuantity, string $reason, ?Model $reference = null, ?float $unitCost = null, ?string $notes = null): StockMovement
    {
        return DB::transaction(function () use ($product, $baseQuantity, $reason, $reference, $unitCost, $notes) {
            $product = $product->lockForUpdate()->findOrFail($product->id);

            $this->assertAvailable($product, $baseQuantity);

            $balanceAfter = (float) $product->stock_quantity - $baseQuantity;

            if ($product->track_stock) {
                $product->update(['stock_quantity' => $balanceAfter]);
            }

            return $this->recordMovement($product, StockMovement::TYPE_OUT, $baseQuantity, $reason, $balanceAfter, $reference, $unitCost, $notes);
        });
    }

    /**
     * Assert that a product has enough stock.
     *
     * @throws ValidationException
     */
    public function assertAvailable(Product $product, float $baseQuantity): void
    {
        if (! $product->track_stock) {
            return;
        }

        $available = (float) $product->stock_quantity;

        if ($available < $baseQuantity) {
            throw ValidationException::withMessages([
                'stock' => "Insufficient stock for {$product->name}. Available: {$available}, requested: {$baseQuantity}.",
            ]);
        }
    }

    /**
     * Get the current stock quantity for a product.
     */
    public function currentStock(Product $product): float
    {
        return (float) $product->fresh()->stock_quantity;
    }

    /**
     * Record a stock movement and return it.
     */
    protected function recordMovement(
        Product $product,
        string $type,
        float $baseQuantity,
        string $reason,
        float $balanceAfter,
        ?Model $reference = null,
        ?float $unitCost = null,
        ?string $notes = null
    ): StockMovement {
        $data = [
            'product_id' => $product->id,
            'type' => $type,
            'reason' => $reason,
            'base_quantity' => $baseQuantity,
            'balance_after' => $balanceAfter,
            'unit_cost' => $unitCost,
            'notes' => $notes,
            'user_id' => auth()->id(),
        ];

        if ($reference) {
            $data['reference_type'] = get_class($reference);
            $data['reference_id'] = $reference->id;
        }

        return StockMovement::create($data);
    }
}
