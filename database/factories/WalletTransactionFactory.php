<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalletTransaction>
 */
class WalletTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => Wallet::inRandomOrder()->first()->id, // Use a random existing wallet
            'amount' => $this->faker->randomFloat(2, 1, 500), // Random amount between 1 and 500
            'type' => $this->faker->randomElement(['ride_payment', 'ride_income', 'platform_commission']),
            'description' => $this->faker->sentence,
        ];
    }
}
