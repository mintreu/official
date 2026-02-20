<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Licensing\License;
use App\Models\Saas\LicensedSite;
use App\Services\Saas\ChildVendorProvisioningService;
use App\Services\Saas\SaasInsightsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaasSiteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $sites = LicensedSite::query()
            ->where('user_id', $user->id)
            ->with(['product:id,slug,title', 'plan:id,slug,name', 'license:id,license_key,expires_at,is_active'])
            ->latest('id')
            ->get();

        return response()->json([
            'data' => $sites->map(fn (LicensedSite $site) => $this->mapSiteCard($site))->values(),
        ]);
    }

    public function create(Request $request, int $licenseId, ChildVendorProvisioningService $provisioningService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'site_slug' => ['required', 'string', 'max:120'],
            'site_name' => ['required', 'string', 'max:160'],
            'site_domain' => ['nullable', 'string', 'max:255'],
            'vendor_name' => ['nullable', 'string', 'max:160'],
        ]);

        /** @var License $license */
        $license = $user->licenses()
            ->with(['product', 'plan', 'apiKey'])
            ->findOrFail($licenseId);

        if (! $license->is_active || $license->isExpired()) {
            return response()->json([
                'message' => 'This license is not active. Renew subscription before creating a new site.',
            ], 422);
        }

        $planLimits = (array) ($license->plan?->limits ?? []);
        $productMeta = (array) ($license->product?->meta ?? []);
        $maxSites = (int) ($planLimits['sites'] ?? $productMeta['included_sites'] ?? 1);
        $usedSites = LicensedSite::query()->where('license_id', $license->id)->count();

        if ($maxSites > 0 && $usedSites >= $maxSites) {
            return response()->json([
                'message' => 'Site limit reached for current plan. Buy additional site addon or upgrade plan.',
                'data' => [
                    'max_sites' => $maxSites,
                    'used_sites' => $usedSites,
                ],
            ], 422);
        }

        $result = $provisioningService->provisionAdditionalSite(
            $license,
            $license->product,
            $user,
            $license->plan,
            $license->apiKey,
            [
                'site_slug' => (string) $validated['site_slug'],
                'site_name' => (string) $validated['site_name'],
                'site_domain' => $validated['site_domain'] ?? null,
                'vendor_name' => $validated['vendor_name'] ?? null,
            ]
        );

        $site = LicensedSite::query()
            ->where('license_id', $license->id)
            ->where('site_slug', (string) $validated['site_slug'])
            ->with(['product:id,slug,title', 'plan:id,slug,name', 'license:id,license_key,expires_at,is_active'])
            ->first();

        return response()->json([
            'message' => $result['status'] === 'success' ? 'Site provisioned successfully.' : 'Site provisioning failed.',
            'provisioning' => $result,
            'data' => $site ? $this->mapSiteCard($site) : null,
        ], $result['status'] === 'success' ? 201 : 422);
    }

    public function show(Request $request, string $siteUuid, SaasInsightsService $insightsService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $site = LicensedSite::query()
            ->where('user_id', $user->id)
            ->where(function ($q) use ($siteUuid) {
                $q->where('site_uuid', $siteUuid)->orWhere('site_slug', $siteUuid);
            })
            ->with(['product:id,slug,title', 'plan:id,slug,name', 'license:id,license_key,expires_at,is_active'])
            ->firstOrFail();

        $insights = $insightsService->siteCard(
            $site->site_uuid ?: $site->site_slug,
            (string) $site->source_project,
            (int) $site->vendor_id
        );

        return response()->json([
            'data' => array_merge($this->mapSiteCard($site), ['insights' => $insights]),
        ]);
    }

    public function overview(Request $request, SaasInsightsService $insightsService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $minutes = max(1, min(60 * 24 * 30, (int) $request->query('minutes', 60 * 24)));

        return response()->json([
            'data' => $insightsService->userOverview($user->id, $minutes),
        ]);
    }

    public function projectInsights(Request $request, string $project, SaasInsightsService $insightsService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $minutes = max(1, min(60 * 24 * 30, (int) $request->query('minutes', 60 * 24)));

        return response()->json([
            'data' => $insightsService->projectForUser($user->id, $project, $minutes),
        ]);
    }

    public function vendorInsights(Request $request, int $vendorId, SaasInsightsService $insightsService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $hasAccess = LicensedSite::query()
            ->where('user_id', $user->id)
            ->where('vendor_id', $vendorId)
            ->exists();

        if (! $hasAccess) {
            return response()->json(['message' => 'Vendor access denied.'], 403);
        }

        $minutes = max(1, min(60 * 24 * 30, (int) $request->query('minutes', 60 * 24)));

        return response()->json([
            'data' => $insightsService->vendor($vendorId, $minutes),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapSiteCard(LicensedSite $site): array
    {
        return [
            'id' => $site->id,
            'source_project' => $site->source_project,
            'vendor' => [
                'id' => $site->vendor_id,
                'uuid' => $site->vendor_uuid,
                'slug' => $site->vendor_slug,
                'name' => $site->vendor_name,
            ],
            'site' => [
                'id' => $site->site_id,
                'uuid' => $site->site_uuid,
                'slug' => $site->site_slug,
                'name' => $site->site_name,
                'domain' => $site->site_domain,
                'status' => $site->status,
            ],
            'license' => [
                'id' => $site->license_id,
                'key' => $site->license?->license_key,
                'expires_at' => $site->license?->expires_at?->toISOString(),
                'is_active' => (bool) $site->license?->is_active,
            ],
            'product' => [
                'id' => $site->product_id,
                'slug' => $site->product?->slug,
                'title' => $site->product?->title,
            ],
            'plan' => $site->plan ? [
                'id' => $site->plan_id,
                'slug' => $site->plan->slug,
                'name' => $site->plan->name,
            ] : null,
            'provisioned_at' => $site->provisioned_at?->toISOString(),
            'last_seen_at' => $site->last_seen_at?->toISOString(),
        ];
    }
}
