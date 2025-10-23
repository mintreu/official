<?php

namespace Database\Seeders;


use App\Models\Plugin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PluginSeeder extends Seeder
{
    public function run(): void
    {
        $repos = [
            ['title' => 'Debugbar',         'git' => 'https://github.com/barryvdh/laravel-debugbar.git'],
            ['title' => 'Dom Pdf',          'git' => 'https://github.com/barryvdh/laravel-dompdf.git'],
            ['title' => 'EaseBuzz Payment', 'git' => 'https://github.com/mintreu/laravel-easebuzz.git', 'branch' => 'dev'],
            ['title' => 'Custom Branch',    'git' => 'https://github.com/laravel/laravel.git', 'branch' => '10.x'],
            ['title' => 'Local Plugin',     'src' => storage_path('plugins/sample.zip')],
            ['title' => 'filament-log-manager', 'git' => 'https://github.com/filipfonal/filament-log-manager/archive/refs/tags/2.1.0.zip']
        ];

        foreach ($repos as $config) {
            $plugin = Plugin::createFromSource($config);

            if ($plugin) {
                $this->command?->info("✅ Seeded plugin: {$plugin->name}");
            } else {
                $this->command?->error("❌ Failed: {$config['title']}");
            }
        }
    }
}
