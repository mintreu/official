<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ApiSpace;
use App\Models\Api\ApiKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApiSpaceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $spaces = ApiSpace::query()
            ->withTrashed()
            ->with(['apiKey.plan', 'product'])
            ->where('user_id', $user->id)
            ->when($request->filled('api_key_id'), fn ($query) => $query->where('api_key_id', (int) $request->integer('api_key_id')))
            ->when(
                $request->filled('status'),
                function ($query) use ($request) {
                    $status = (string) $request->query('status');
                    if ($status === 'disabled') {
                        $query->onlyTrashed();
                        return;
                    }
                    $query->whereNull('deleted_at')->where('status', $status);
                }
            )
            ->orderByDesc('updated_at')
            ->get();

        return response()->json([
            'data' => $spaces->map(fn (ApiSpace $space) => $this->mapSpace($space))->all(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($request->filled('website')) {
            $request->merge([
                'website' => $this->normalizeWebsite((string) $request->input('website')),
            ]);
        }

        $validated = $request->validate([
            'api_key_id' => ['required', 'integer', Rule::exists('api_keys', 'id')->where('user_id', $user->id)],
            'name' => ['required', 'string', 'max:120'],
            'website' => ['required', 'url', 'max:255'],
            'environment' => ['nullable', Rule::in(['prod', 'dev', 'staging'])],
            'config' => ['nullable', 'array'],
        ]);

        $apiKey = ApiKey::query()
            ->where('id', (int) $validated['api_key_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $space = ApiSpace::create([
            'user_id' => $user->id,
            'api_key_id' => $apiKey->id,
            'product_id' => $apiKey->product_id,
            'name' => $validated['name'],
            'website' => $validated['website'],
            'environment' => $validated['environment'] ?? 'prod',
            'status' => 'active',
            'requests_this_month' => 0,
            'requests_today' => 0,
            'config' => $validated['config'] ?? [],
            'insights' => [
                'avg_latency_ms' => 0,
                'error_rate_percent' => 0,
                'top_endpoint' => null,
            ],
        ]);

        $space->load(['apiKey.plan', 'product']);

        return response()->json([
            'data' => $this->mapSpace($space),
            'message' => 'Space created successfully.',
        ], 201);
    }

    public function update(Request $request, ApiSpace $apiSpace): JsonResponse
    {
        $user = $request->user();

        if (! $user || $apiSpace->user_id !== $user->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if ($request->filled('website')) {
            $request->merge([
                'website' => $this->normalizeWebsite((string) $request->input('website')),
            ]);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'website' => ['sometimes', 'url', 'max:255'],
            'environment' => ['sometimes', Rule::in(['prod', 'dev', 'staging'])],
            'status' => ['sometimes', Rule::in(['active', 'paused'])],
            'config' => ['sometimes', 'array'],
        ]);

        $apiSpace->fill($validated);
        $apiSpace->save();
        $apiSpace->load(['apiKey.plan', 'product']);

        return response()->json([
            'data' => $this->mapSpace($apiSpace),
            'message' => 'Space updated successfully.',
        ]);
    }

    public function show(Request $request, string $spaceUuid): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $space = $this->resolveOwnedSpace($user->id, $spaceUuid, true);

        return response()->json([
            'data' => $this->mapSpace($space),
        ]);
    }

    public function destroy(Request $request, string $spaceUuid): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $space = $this->resolveOwnedSpace($user->id, $spaceUuid, false);
        $space->status = 'paused';
        $space->save();
        $space->delete();

        return response()->json([
            'message' => 'Site disabled successfully.',
        ]);
    }

    private function resolveOwnedSpace(int $userId, string $spaceUuid, bool $includeDeleted): ApiSpace
    {
        return ApiSpace::query()
            ->when($includeDeleted, fn ($query) => $query->withTrashed())
            ->with(['apiKey.plan', 'product'])
            ->where('user_id', $userId)
            ->where('uuid', $spaceUuid)
            ->firstOrFail();
    }

    private function mapSpace(ApiSpace $space): array
    {
        return [
            'uuid' => $space->uuid,
            'name' => $space->name,
            'website' => $space->website,
            'environment' => $space->environment,
            'status' => $space->trashed() ? 'disabled' : $space->status,
            'deleted_at' => $space->deleted_at?->toIsoString(),
            'requests_this_month' => $space->requests_this_month,
            'requests_today' => $space->requests_today,
            'last_request_at' => $space->last_request_at?->toIsoString(),
            'config' => $space->config ?? [],
            'insights' => $space->insights ?? [],
            'api_key' => $space->apiKey ? [
                'id' => $space->apiKey->id,
                'key_prefix' => $space->apiKey->key_prefix,
                'name' => $space->apiKey->name,
            ] : null,
            'plan' => $space->apiKey?->plan ? [
                'name' => $space->apiKey->plan->name,
                'requests_per_month' => $space->apiKey->plan->requests_per_month,
                'requests_per_day' => $space->apiKey->plan->requests_per_day,
            ] : null,
            'product' => $space->product ? [
                'id' => $space->product->id,
                'slug' => $space->product->slug,
                'title' => $space->product->title,
                'type' => $space->product->type->value,
            ] : null,
        ];
    }

    private function normalizeWebsite(string $website): string
    {
        $normalized = trim($website);
        if ($normalized === '') {
            return $normalized;
        }

        $normalized = preg_replace('/^ht+tps:\/\//i', 'https://', $normalized) ?? $normalized;
        $normalized = preg_replace('/^https:\/\//i', 'https://', $normalized) ?? $normalized;
        $normalized = preg_replace('/^ttps:\/\//i', 'https://', $normalized) ?? $normalized;
        $normalized = preg_replace('/^htp:\/\//i', 'http://', $normalized) ?? $normalized;

        if (! preg_match('/^https?:\/\//i', $normalized)) {
            $normalized = 'https://'.$normalized;
        }

        return $normalized;
    }
}
