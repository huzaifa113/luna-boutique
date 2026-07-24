<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(fake()->bothify('####-????-####')),
            'status' => fake()->randomElement(Order::STATUSES),
            'payment_status' => fake()->randomElement(Order::PAYMENT_STATUSES),
            'payment_method' => fake()->randomElement(Order::PAYMENT_METHODS),
            'subtotal' => fake()->randomFloat(2, 50, 500),
            'discount' => fake()->randomFloat(2, 0, 50),
            'tax' => fake()->randomFloat(2, 5, 50),
            'shipping' => fake()->randomFloat(2, 0, 15),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PENDING,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => Order::PAYMENT_STATUS_PAID,
        ]);
    }
}