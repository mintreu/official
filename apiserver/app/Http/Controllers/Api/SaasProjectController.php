<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Saas\SaasBridgeHttpClient;
use App\Services\Saas\SaasProjectInsightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaasProjectController extends Controller
{
    public function index(Request $request, SaasProjectInsightService $insightService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $minutes = max(1, min(60 * 24 * 30, (int) $request->query('minutes', 1440)));

        return response()->json([
            'data' => $insightService->listForUser($user->id, $minutes),
        ]);
    }

    public function show(Request $request, string $project, SaasProjectInsightService $insightService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $minutes = max(1, min(60 * 24 * 30, (int) $request->query('minutes', 1440)));

        return response()->json([
            'data' => $insightService->forUserProject($user->id, $project, $minutes),
        ]);
    }

    public function ping(Request $request, string $project, SaasBridgeHttpClient $bridgeClient): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $result = $bridgeClient->getPublic($project, '/api/health', 'project.health.ping');

        return response()->json([
            'success' => (bool) ($result['ok'] ?? false),
            'message' => (string) ($result['message'] ?? ''),
            'data' => $result,
        ], ($result['ok'] ?? false) ? 200 : 422);
    }
}
