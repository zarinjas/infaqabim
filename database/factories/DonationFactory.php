<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'donor_name' => fake()->name(),
            'donor_email' => fake()->safeEmail(),
            'donor_phone' => fake()->phoneNumber(),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'reference_number' => null,
            'receipt_image' => null,
            'status' => 'pending',
            'payment_gateway' => 'manual',
        ];
    }
}
