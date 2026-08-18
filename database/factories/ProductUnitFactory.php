<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductUnit>
 */
class ProductUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
            'factor' => fake()->randomFloat(4, 1, 100),
            'is_base' => false,
            'is_default_purchase' => false,
            'is_default_sale' => false,
        ];
    }

    public function base(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_base' => true,
            'factor' => 1,
        ]);
    }

    public function bag(int $factor = 20): static
    {
        return $this->state(fn (array $attributes) => [
            'factor' => $factor,
        ]);
    }
}
