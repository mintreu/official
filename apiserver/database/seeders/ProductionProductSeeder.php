<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([RevenuePortfolioSeeder::class]);
    }
}

