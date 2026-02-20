<?php

namespace Tests\Feature\Api\Internal;

use App\Models\Saas\SaasProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaasProjectHeartbeatTest extends TestCase
{
    use RefreshDatabase;

    public function test_child_project_can_send_signed_heartbeat(): void
    {
        config()->set('services.mintreu_saas.projects.shopcore-commerce-api', [
            'internal_key' => 'test-key',
            'internal_secret' => 'test-secret',
            'local_base_url' => 'http://shopcore.test',
        ]);
        config()->set('services.mintreu_saas.max_skew_seconds', 300);

        $payload = [
            'machine' => [
                'host' => 'shopcore-node-01',
                'os' => 'linux',
                'memory_total_mb' => 2048,
            ],
            'runtime' => [
                'node' => '20.10.0',
                'nuxt' => '3.15.1',
            ],
            'health' => [
                'status' => 'ok',
            ],
        ];

        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $timestamp = (string) now()->timestamp;
        $path = 'api/internal/saas/project/heartbeat';
        $signature = hash_hmac('sha256', "{$timestamp}.POST.{$path}.{$body}", 'test-secret');

        $response = $this->withHeaders([
            'X-Mintreu-Project' => 'shopcore-commerce-api',
            'X-Mintreu-Key' => 'test-key',
            'X-Mintreu-Timestamp' => $timestamp,
            'X-Mintreu-Signature' => $signature,
            'Content-Type' => 'application/json',
        ])->postJson('/api/internal/saas/project/heartbeat', $payload);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $project = SaasProject::query()->where('slug', 'shopcore-commerce-api')->first();
        $this->assertNotNull($project);
        $this->assertNotNull($project->last_heartbeat_at);
        $this->assertSame('shopcore-node-01', data_get($project->last_machine_info, 'machine.host'));
    }
}
