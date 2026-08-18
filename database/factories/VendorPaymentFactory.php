<?php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\VendorPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorPayment>
 */
class VendorPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'amount' => fake()->randomFloat(2, 1000, 50000),
            'payment_date' => fake()->date(),
            'method' => fake()->randomElement(VendorPayment::METHODS),
            'notes' => fake()->sentence(),
        ];
    }
}