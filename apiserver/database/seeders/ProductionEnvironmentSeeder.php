<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionEnvironmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProductionProjectSeeder::class,
            ProductionCaseStudySeeder::class,
            ProductionProductSeeder::class,
        ]);
    }
}

