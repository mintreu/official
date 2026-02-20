<?php

namespace App\Services\Saas;

use App\Models\Saas\SaasProject;

class SaasProjectRegistryService
{
    /**
     * @return array<string, mixed>
     */
    public function resolve(string $projectSlug): array
    {
        $configProject = (array) data_get(config('services.mintreu_saas.projects', []), $projectSlug, []);
        $db = SaasProject::query()->where('slug', $projectSlug)->first();

        $isLocal = app()->environment(['local', 'development', 'testing'])
            || str_contains((string) config('app.url', ''), 'mintreu.test');

        $baseUrl = $isLocal
            ? (string) ($configProject['local_base_url'] ?? $configProject['base_url'] ?? '')
            : (string) ($configProject['production_base_url'] ?? $configProject['base_url'] ?? '');

        return [
            'slug' => $projectSlug,
            'name' => (string) ($db?->name ?? str($projectSlug)->replace('-', ' ')->title()),
            'base_url' => (string) ($db?->base_url ?? $baseUrl),
            'provision_path' => (string) ($configProject['provision_path'] ?? '/api/internal/saas/vendors/provision'),
            'internal_key' => (string) ($db?->internal_key ?? $configProject['internal_key'] ?? ''),
            'internal_secret' => (string) ($db?->internal_secret ?? $configProject['internal_secret'] ?? ''),
            'timeout_seconds' => max(5, (int) ($configProject['timeout_seconds'] ?? 15)),
            'is_active' => (bool) ($db?->is_active ?? true),
            'last_heartbeat_at' => $db?->last_heartbeat_at?->toISOString(),
            'last_machine_info' => (array) ($db?->last_machine_info ?? []),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function allActive(): array
    {
        $projectKeys = array_keys((array) config('services.mintreu_saas.projects', []));

        return collect($projectKeys)
            ->map(fn (string $slug): array => $this->resolve($slug))
            ->filter(fn (array $project): bool => ($project['is_active'] ?? false) === true)
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $machineInfo
     */
    public function markHeartbeat(string $projectSlug, array $machineInfo = []): SaasProject
    {
        $resolved = $this->resolve($projectSlug);

        return SaasProject::query()->updateOrCreate(
            ['slug' => $projectSlug],
            [
                'name' => (string) ($resolved['name'] ?? $projectSlug),
                'base_url' => (string) ($resolved['base_url'] ?? ''),
                'internal_key' => (string) ($resolved['internal_key'] ?? ''),
                'internal_secret' => (string) ($resolved['internal_secret'] ?? ''),
                'is_active' => true,
                'last_heartbeat_at' => now(),
                'last_machine_info' => $machineInfo,
            ]
        );
    }
}
