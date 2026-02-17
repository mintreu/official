<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            ProjectSeeder::class,
            CaseStudySeeder::class,
            ProductSeeder::class,
            DashboardDemoSeeder::class,
            FreebieSampleSeeder::class,
            ArticleSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
