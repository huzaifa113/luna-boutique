<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory();

        return [
            'order_id' => Order::factory(),
            'product_id' => $product,
            'product_name' => $product instanceof Product ? $product->name : fake()->word(),
            'product_sku' => $product instanceof Product ? $product->sku : fake()->unique()->ean8(),
            'quantity' => fake()->numberBetween(1, 3),
            'unit_price' => fake()->randomFloat(2, 20, 200),
            'total_price' => fn (array $attributes) => $attributes['quantity'] * $attributes['unit_price'],
        ];
    }
}