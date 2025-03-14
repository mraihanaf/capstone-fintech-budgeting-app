<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
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
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'amount' => fake()->randomFloat(2, 500, 500000),
            'type' => fake()->randomElement(['income', 'expense']),
            'description' => fake()->optional()->sentence(),
            'transaction_date' => fake()->date(),
            'is_recurring' => fake()->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
