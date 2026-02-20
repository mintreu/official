<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Services\Saas\SaasInsightsService;
use App\Services\Saas\SaasLicenseCredentialService;
use App\Services\Saas\SaasPlanResolverService;
use App\Services\Saas\SaasSignatureService;
use App\Services\Saas\SaasStatIngestionService;
use App\Services\Saas\SaasSyncLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaasBridgeController extends Controller
{
    public function ingestSiteStats(
        Request $request,
        SaasSignatureService $signatureService,
        SaasStatIngestionService $ingestionService,
        SaasSyncLogService $syncLogService
    ): JsonResponse {
        $authorized = $this->authorizeInternal($request, $signatureService);
        if ($authorized instanceof JsonResponse) {
            return $authorized;
        }
        $project = $authorized;

        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
            'site_uuid' => ['nullable', 'string'],
            'site_slug' => ['nullable', 'string'],
            'vendor_id' => ['nullable', 'integer'],
            'window_start' => ['nullable', 'string'],
            'window_end' => ['nullable', 'string'],
            'metrics' => ['required', 'array'],
            'metrics.orders_count' => ['nullable', 'integer'],
            'metrics.new_users_count' => ['nullable', 'integer'],
            'metrics.revenue_paise' => ['nullable', 'integer'],
            'metrics.requests_count' => ['nullable', 'integer'],
            'metrics.errors_count' => ['nullable', 'integer'],
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
        ]);

        $event = $ingestionService->ingest($validated, $project);
        $syncLogService->write(
            $project,
            'inbound',
            'site-stats.ingest',
            'success',
            200,
            $request->path(),
            'Site stat ingested.',
            $validated,
            ['event_id' => $event->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'SaaS site stat ingested.',
            'data' => [
                'id' => $event->id,
                'source_project' => $event->source_project,
                'site_id' => $event->site_id,
                'site_uuid' => $event->site_uuid,
                'vendor_id' => $event->vendor_id,
                'received_at' => $event->received_at?->toISOString(),
            ],
        ]);
    }

    public function siteInsights(
        string $siteUuid,
        Request $request,
        SaasSignatureService $signatureService,
        SaasInsightsService $insightsService
    ): JsonResponse {
        $authorized = $this->authorizeInternal($request, $signatureService);
        if ($authorized instanceof JsonResponse) {
            return $authorized;
        }

        $minutes = max(1, (int) $request->query('minutes', 60));

        return response()->json([
            'success' => true,
            'data' => $insightsService->site($siteUuid, $minutes),
        ]);
    }

    public function vendorInsights(
        int $vendorId,
        Request $request,
        SaasSignatureService $signatureService,
        SaasInsightsService $insightsService
    ): JsonResponse {
        $authorized = $this->authorizeInternal($request, $signatureService);
        if ($authorized instanceof JsonResponse) {
            return $authorized;
        }

        $minutes = max(1, (int) $request->query('minutes', 60));

        return response()->json([
            'success' => true,
            'data' => $insightsService->vendor($vendorId, $minutes),
        ]);
    }

    public function overviewInsights(
        Request $request,
        SaasSignatureService $signatureService,
        SaasInsightsService $insightsService
    ): JsonResponse {
        $authorized = $this->authorizeInternal($request, $signatureService);
        if ($authorized instanceof JsonResponse) {
            return $authorized;
        }

        $minutes = max(1, (int) $request->query('minutes', 60));

        return response()->json([
            'success' => true,
            'data' => $insightsService->overview($minutes),
        ]);
    }

    public function resolvePlan(
        Request $request,
        SaasSignatureService $signatureService,
        SaasPlanResolverService $planResolverService
    ): JsonResponse {
        $authorized = $this->authorizeInternal($request, $signatureService);
        if ($authorized instanceof JsonResponse) {
            return $authorized;
        }

        $validated = $request->validate([
            'project' => ['required', 'string'],
            'product_slug' => ['required', 'string'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $planResolverService->resolve(
                (string) $validated['project'],
                (string) $validated['product_slug']
            ),
        ]);
    }

    public function checkLicense(
        Request $request,
        SaasSignatureService $signatureService,
        SaasLicenseCredentialService $credentialService,
        SaasPlanResolverService $planResolverService
    ): JsonResponse {
        $authorized = $this->authorizeInternal($request, $signatureService);
        if ($authorized instanceof JsonResponse) {
            return $authorized;
        }
        $project = $authorized;

        $validated = $request->validate([
            'site.id' => ['nullable', 'integer'],
            'site.uuid' => ['nullable', 'string'],
            'site.vendor_id' => ['nullable', 'integer'],
            'site.slug' => ['nullable', 'string'],
            'site.license_key' => ['required', 'string'],
            'site.license_secret' => ['required', 'string'],
            'product_slug' => ['required', 'string'],
        ]);

        $site = (array) ($validated['site'] ?? []);
        $licenseKey = (string) ($site['license_key'] ?? '');
        $licenseSecret = (string) ($site['license_secret'] ?? '');

        if (! $credentialService->isValid($project, $licenseKey, $licenseSecret)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid license credentials.',
                'data' => [
                    'status' => 'invalid',
                    'plan' => null,
                    'features' => [],
                ],
            ], 401);
        }

        $resolved = $planResolverService->resolve($project, (string) $validated['product_slug']);
        $data = (array) ($resolved['data'] ?? []);
        $limits = (array) ($data['limits'] ?? []);

        return response()->json([
            'success' => true,
            'message' => 'License valid.',
            'data' => [
                'status' => 'valid',
                'plan' => (string) ($data['plan_slug'] ?? 'default'),
                'valid_until' => now()->addDays(30)->toISOString(),
                'features' => $limits,
                'subscription_snapshot' => [
                    'plan_version' => $data['plan_version'] ?? null,
                    'effective_from' => $data['effective_from'] ?? null,
                    'max_sites' => $limits['max_sites'] ?? null,
                    'max_users' => $limits['max_users'] ?? null,
                    'max_products' => $limits['max_products'] ?? null,
                    'max_orders_per_day' => $limits['max_orders_per_day'] ?? null,
                    'rate_limits' => (array) ($data['rate_limits'] ?? []),
                ],
                'rate_limits' => (array) ($data['rate_limits'] ?? []),
            ],
        ]);
    }

    private function authorizeInternal(Request $request, SaasSignatureService $signatureService): JsonResponse|string
    {
        $project = $signatureService->resolveAuthorizedProject($request);
        if (is_string($project) && $project !== '') {
            return $project;
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid internal signature.',
        ], 401);
    }
}
