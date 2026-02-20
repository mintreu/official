<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $environmentSeeder = app()->environment(['local', 'development', 'testing'])
            ? LocalEnvironmentSeeder::class
            : ProductionEnvironmentSeeder::class;

        $this->call([
            UserSeeder::class,
            LegacyDemoCleanupSeeder::class,
            OfficialUserSeeder::class,
            AdminSeeder::class,
            IntegrationSeeder::class,
            SaasProjectSeeder::class,
            $environmentSeeder,
            DashboardDemoSeeder::class,
            FreebieSampleSeeder::class,
            ArticleSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
