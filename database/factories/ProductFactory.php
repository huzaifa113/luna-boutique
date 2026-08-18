<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Premium Basmati Rice', 'Organic Wheat Flour', 'Cooking Oil 5L', 'Sugar 1kg',
            'Tea Premium', 'Milk Powder 500g', 'Canned Beans', 'Pasta Penne',
            'Tomato Ketchup', 'Mayonnaise Jar', 'Chicken Spices', 'Salt Iodized',
            'Mineral Water 1.5L', 'Orange Juice', 'Biscuits Pack', 'Cereal Box',
            'Chocolate Bar', 'Instant Noodles', 'Cooking Sauce', 'Olive Oil 500ml',
        ];

        $name = $this->faker->unique()->randomElement($names);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'sku' => 'SKU-' . strtoupper($this->faker->unique()->bothify('??####')),
            'stock_quantity' => $this->faker->randomFloat(0, 20, 500),
            'cost_price' => $this->faker->randomFloat(2, 50, 5000),
            'price' => $this->faker->randomFloat(2, 80, 8000),
            'track_stock' => true,
            'is_active' => true,
            'short_description' => $this->faker->sentence(),
        ];
    }
}