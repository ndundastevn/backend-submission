<?php

namespace Database\Factories;

use App\TransactionType;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => Wallet::factory(),
            'amount' => fake()->randomFloat(2, 0.01, 1000),
            'type' => fake()->randomElement(TransactionType::cases()),
            'description' => fake()->optional(0.7)->sentence(),
            'date' => fake()->dateTimeThisYear()->format('Y-m-d'),
        ];
    }
}
