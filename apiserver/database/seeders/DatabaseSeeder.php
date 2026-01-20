<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Default Admin

        $this->call([
            AdminSeeder::class,
            ProjectSeeder::class,
            CaseStudySeeder::class,
            ProductSeeder::class,
            FreebieSampleSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
