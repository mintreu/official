<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Services\Saas\SaasProjectRegistryService;
use App\Services\Saas\SaasSignatureService;
use App\Services\Saas\SaasSyncLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaasProjectController extends Controller
{
    public function heartbeat(
        Request $request,
        SaasSignatureService $signatureService,
        SaasProjectRegistryService $projectRegistry,
        SaasSyncLogService $syncLog
    ): JsonResponse {
        $project = $signatureService->resolveAuthorizedProject($request);
        if (! $project) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid internal signature.',
            ], 401);
        }

        $validated = $request->validate([
            'machine' => ['nullable', 'array'],
            'machine.host' => ['nullable', 'string'],
            'machine.os' => ['nullable', 'string'],
            'machine.kernel' => ['nullable', 'string'],
            'machine.cpu' => ['nullable', 'string'],
            'machine.memory_total_mb' => ['nullable', 'integer'],
            'machine.memory_used_mb' => ['nullable', 'integer'],
            'runtime' => ['nullable', 'array'],
            'runtime.node' => ['nullable', 'string'],
            'runtime.nuxt' => ['nullable', 'string'],
            'runtime.app_version' => ['nullable', 'string'],
            'health' => ['nullable', 'array'],
            'health.status' => ['nullable', 'string'],
            'health.version' => ['nullable', 'string'],
        ]);

        $machine = [
            'machine' => (array) ($validated['machine'] ?? []),
            'runtime' => (array) ($validated['runtime'] ?? []),
            'health' => (array) ($validated['health'] ?? []),
        ];

        $projectModel = $projectRegistry->markHeartbeat($project, $machine);
        $syncLog->write($project, 'inbound', 'project.heartbeat', 'success', 200, $request->path(), 'Project heartbeat received.', $validated, [
            'project_id' => $projectModel->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received.',
            'data' => [
                'project' => $project,
                'last_heartbeat_at' => $projectModel->last_heartbeat_at?->toISOString(),
            ],
        ]);
    }
}
