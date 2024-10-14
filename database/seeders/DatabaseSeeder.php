<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        $this->call([

            ServiceSeeder::class,
            CategorySeeder::class,
            ProjectSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            PlanSeeder::class,
            OrderSeeder::class,
        ]);

    }
}
