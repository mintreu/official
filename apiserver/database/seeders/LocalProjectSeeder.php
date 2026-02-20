<?php

namespace Database\Seeders;

use App\Models\Content\Project;
use Illuminate\Database\Seeder;

class LocalProjectSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([ProductionProjectSeeder::class]);

        Project::query()
            ->whereNotNull('live_url')
            ->get()
            ->each(function (Project $project): void {
                $project->update([
                    'live_url' => str_replace('mintreu.com', 'mintreu.test', (string) $project->live_url),
                ]);
            });
    }
}

