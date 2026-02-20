<?php

namespace Database\Seeders;

use App\Models\Saas\SaasProject;
use Illuminate\Database\Seeder;

class SaasProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = (array) config('services.mintreu_saas.projects', []);

        foreach ($projects as $slug => $config) {
            $data = (array) $config;
            $isLocal = app()->environment(['local', 'development', 'testing'])
                || str_contains((string) config('app.url', ''), 'mintreu.test');

            $baseUrl = $isLocal
                ? (string) ($data['local_base_url'] ?? $data['base_url'] ?? '')
                : (string) ($data['production_base_url'] ?? $data['base_url'] ?? '');

            SaasProject::query()->updateOrCreate(
                ['slug' => (string) $slug],
                [
                    'name' => str((string) $slug)->replace('-', ' ')->title()->toString(),
                    'base_url' => $baseUrl,
                    'internal_key' => (string) ($data['internal_key'] ?? ''),
                    'internal_secret' => (string) ($data['internal_secret'] ?? ''),
                    'is_active' => true,
                    'meta' => [
                        'provision_path' => (string) ($data['provision_path'] ?? '/api/internal/saas/vendors/provision'),
                        'timeout_seconds' => (int) ($data['timeout_seconds'] ?? 15),
                    ],
                ]
            );
        }
    }
}
