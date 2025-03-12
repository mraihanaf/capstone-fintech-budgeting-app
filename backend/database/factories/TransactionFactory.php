<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->unique(),
            'category_id' => fake()->unique(),
            'amount' => fake()->unique(),
            'type' => fake()->randomElement(['income', 'expense']),
            'description' => fake()->sentences(),
            'transaction_date' => fake()->date(),
            'is_recurring' => fake()->boolean()
        ];
    }
}