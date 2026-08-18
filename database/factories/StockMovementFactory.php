<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
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
            'type' => fake()->randomElement([StockMovement::TYPE_IN, StockMovement::TYPE_OUT]),
            'reason' => fake()->randomElement(StockMovement::REASONS),
            'base_quantity' => fake()->randomFloat(3, 1, 100),
            'balance_after' => fake()->randomFloat(3, 1, 100),
            'user_id' => User::factory(),
        ];
    }
}
