<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'invoice_number' => 'INV-'.str_pad((string) fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'sale_date' => fake()->date(),
            'status' => Sale::STATUS_DRAFT,
            'subtotal' => 0,
            'shortage_adjustment' => 0,
            'discount' => 0,
            'tax' => 0,
            'delivery_charges' => 0,
            'total' => 0,
            'shortage_cost' => 0,
            'payment_status' => 'unpaid',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Sale::STATUS_DRAFT,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Sale::STATUS_COMPLETED,
        ]);
    }
}