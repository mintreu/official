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
            IntegrationSeeder::class,
            ProductionProjectSeeder::class,
            ProductionCaseStudySeeder::class,
            ProductionProductSeeder::class,
            DashboardDemoSeeder::class,
            FreebieSampleSeeder::class,
            ArticleSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
