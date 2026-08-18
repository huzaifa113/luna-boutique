<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseItem>
 */
class PurchaseItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->randomFloat(3, 1, 50);
        $factor = fake()->randomFloat(4, 1, 20);
        $rate = fake()->randomFloat(2, 100, 5000);
        $grossBaseQty = round($quantity * $factor, 3);
        $grossAmount = round($quantity * $rate, 2);
        $baseUnitRate = $grossBaseQty > 0 ? round($grossAmount / $grossBaseQty, 4) : 0;
        $shortageQty = 0;
        $receivedBaseQty = $grossBaseQty - $shortageQty;
        $shortageAmount = round($shortageQty * $baseUnitRate, 2);
        $netAmount = $grossAmount - $shortageAmount;

        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => Product::factory(),
            'product_name' => fake()->word(),
            'unit_id' => Unit::factory(),
            'unit_name' => fake()->word(),
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
        ];
    }

    public function withShortage(float $shortageQty): static
    {
        return $this->state(fn (array $attributes) => [
            'shortage_quantity' => $shortageQty,
            'received_base_quantity' => round((float) $attributes['gross_base_quantity'] - $shortageQty, 3),
            'shortage_amount' => round($shortageQty * (float) $attributes['base_unit_rate'], 2),
            'net_amount' => round((float) $attributes['gross_amount'] - round($shortageQty * (float) $attributes['base_unit_rate'], 2), 2),
        ]);
    }
}
