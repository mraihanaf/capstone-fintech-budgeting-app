<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Log;
use App\Models\Recommendation;
use App\Models\Report;
use App\Models\Target;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        Category::factory()->count(5)->create();
        Transaction::factory()->count(50)->create();
        // Recommendation::factory()->count(10)->create();
        // Log::factory()->count(30)->create();
    }
}
