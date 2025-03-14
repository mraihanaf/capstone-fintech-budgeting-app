<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
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
            'report_type' => fake()->randomElement(['monthly', 'yearly']),
            'report_file' => fake()->word() . '.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
