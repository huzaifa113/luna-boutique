<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerPayment>
 */
class CustomerPaymentFactory extends Factory
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
            'amount' => fake()->randomFloat(2, 500, 20000),
            'payment_date' => fake()->date(),
            'method' => fake()->randomElement(CustomerPayment::METHODS),
            'notes' => fake()->sentence(),
        ];
    }
}