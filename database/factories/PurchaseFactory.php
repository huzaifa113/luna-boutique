<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchase>
 */
class PurchaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'invoice_number' => 'PUR-'.str_pad((string) fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'purchase_date' => fake()->date(),
            'status' => Purchase::STATUS_DRAFT,
            'subtotal' => 0,
            'shortage_adjustment' => 0,
            'discount' => 0,
            'tax' => 0,
            'freight' => 0,
            'total' => 0,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Purchase::STATUS_DRAFT,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Purchase::STATUS_CONFIRMED,
        ]);
    }
}
