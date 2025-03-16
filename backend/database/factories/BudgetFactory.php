<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
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
            'budget_limit' => fake()->randomFloat(2, 10000, 5000000),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
