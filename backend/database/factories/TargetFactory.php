<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Target>
 */
class TargetFactory extends Factory
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
            'name' => fake()->sentence(3),
            'target_amount' => fake()->randomFloat(2, 10000, 1000000),
            'saved_amount' => fake()->randomFloat(2, 0, 500000),
            'deadline' => fake()->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
