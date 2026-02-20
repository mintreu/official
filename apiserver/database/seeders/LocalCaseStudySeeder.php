<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocalCaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([ProductionCaseStudySeeder::class]);
    }
}

