<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocalEnvironmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LocalProjectSeeder::class,
            LocalCaseStudySeeder::class,
            LocalRevenuePortfolioSeeder::class,
        ]);
    }
}

