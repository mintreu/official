<?php

namespace App\Services\Saas;

use App\Models\Api\ApiKey;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\Saas\LicensedSite;
use App\Models\User;
use Illuminate\Support\Str;

class ChildVendorProvisioningService
{
    public function __construct(
        private readonly SaasProjectRegistryService $projectRegistry,
        private readonly SaasBridgeHttpClient $bridgeHttpClient
    ) {}

    public function provisionFromSubscription(
        License $license,
        Product $product,
        User $user,
        ?Plan $plan,
        ?ApiKey $apiKey,
        array $context = []
    ): array {
        $projectConfig = $this->resolveProjectConfig($product->slug);
        if (! $projectConfig) {
            return [
                'status' => 'skipped',
                'message' => 'No child project provisioning config found for this product.',
            ];
        }

        $meta = (array) ($license->meta ?? []);
        $provisionMeta = (array) ($meta['provisioning'] ?? []);
        $siteSlug = (string) ($context['site_slug'] ?? $provisionMeta['site_slug'] ?? Str::slug($user->name.'-'.$product->slug.'-'.$license->id));
        $vendorSlug = (string) ($provisionMeta['vendor_slug'] ?? Str::slug($user->name.'-'.$product->slug));
        $siteLicenseKey = (string) ($provisionMeta['site_license_key'] ?? ('msk_'.Str::lower(Str::random(28))));
        $siteLicenseSecret = (string) ($provisionMeta['site_license_secret'] ?? Str::random(48));

        $payload = [
            'vendor' => [
                'slug' => $vendorSlug,
                'name' => (string) ($context['vendor_name'] ?? $user->name ?? 'Vendor'),
                'email' => (string) ($user->email ?? ''),
                'mobile' => (string) ($user->phone ?? ''),
                'external_user_id' => $user->id,
            ],
            'site' => [
                'slug' => $siteSlug,
                'name' => (string) ($context['site_name'] ?? ($product->title.' Site')),
                'domain' => isset($context['site_domain']) ? (string) $context['site_domain'] : null,
                'plan' => $plan?->slug,
            ],
            'license' => [
                'official_license_key' => $license->license_key,
                'site_license_key' => $siteLicenseKey,
                'site_license_secret' => $siteLicenseSecret,
                'api_key_prefix' => $apiKey?->key_prefix,
                'expires_at' => $license->expires_at?->toISOString(),
            ],
            'context' => [
                'product_slug' => $product->slug,
                'license_id' => $license->id,
                'plan_id' => $plan?->id,
                'user_id' => $user->id,
            ],
        ];

        $result = [
            'status' => 'failed',
            'message' => 'Provisioning request was not attempted.',
            'target' => rtrim((string) ($projectConfig['base_url'] ?? ''), '/').'/'.ltrim((string) ($projectConfig['provision_path'] ?? ''), '/'),
        ];

        $response = $this->bridgeHttpClient->post(
            $product->slug,
            (string) $projectConfig['provision_path'],
            $payload,
            'vendor.provision'
        );

        if (($response['ok'] ?? false) === true) {
            $result = [
                'status' => 'success',
                'http_status' => (int) ($response['status'] ?? 200),
                'message' => 'Child vendor/site provisioned.',
                'response' => (array) ($response['data'] ?? []),
                'target' => $result['target'],
            ];
        } else {
            $result = [
                'status' => 'failed',
                'http_status' => isset($response['status']) ? (int) $response['status'] : null,
                'message' => (string) ($response['message'] ?? 'Child provisioning failed.'),
                'response' => (array) ($response['data'] ?? []),
                'target' => $result['target'],
            ];
        }

        $siteSnapshot = $this->persistLicensedSite(
            $license,
            $product,
            $user,
            $plan,
            $result,
            $payload
        );

        $meta['provisioning'] = [
            'vendor_slug' => $vendorSlug,
            'site_slug' => $siteSlug,
            'site_license_key' => $siteLicenseKey,
            'site_license_secret' => $siteLicenseSecret,
            'licensed_site_id' => $siteSnapshot['licensed_site_id'] ?? null,
            'last_result' => $result,
            'updated_at' => now()->toISOString(),
        ];
        $license->meta = $meta;
        $license->save();

        return $result;
    }

    public function provisionAdditionalSite(
        License $license,
        Product $product,
        User $user,
        ?Plan $plan,
        ?ApiKey $apiKey,
        array $context = []
    ): array {
        return $this->provisionFromSubscription($license, $product, $user, $plan, $apiKey, $context);
    }

    private function resolveProjectConfig(string $productSlug): ?array
    {
        $project = $this->projectRegistry->resolve($productSlug);
        $baseUrl = (string) ($project['base_url'] ?? '');
        $internalKey = (string) ($project['internal_key'] ?? '');
        $internalSecret = (string) ($project['internal_secret'] ?? '');

        if ($baseUrl === '' || $internalKey === '' || $internalSecret === '') {
            return null;
        }

        return [
            'base_url' => $baseUrl,
            'provision_path' => (string) ($project['provision_path'] ?? '/api/internal/saas/vendors/provision'),
            'internal_key' => $internalKey,
            'internal_secret' => $internalSecret,
            'timeout_seconds' => max(5, (int) ($project['timeout_seconds'] ?? 15)),
        ];
    }

    /**
     * @param  array<string, mixed>  $result
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function persistLicensedSite(
        License $license,
        Product $product,
        User $user,
        ?Plan $plan,
        array $result,
        array $payload
    ): array {
        $response = (array) ($result['response'] ?? []);
        $responseData = (array) ($response['data'] ?? []);
        $vendorResponse = (array) ($responseData['vendor'] ?? []);
        $siteResponse = (array) ($responseData['site'] ?? []);

        $payloadVendor = (array) ($payload['vendor'] ?? []);
        $payloadSite = (array) ($payload['site'] ?? []);
        $payloadLicense = (array) ($payload['license'] ?? []);
        $payloadContext = (array) ($payload['context'] ?? []);

        $sourceProject = (string) ($payloadContext['product_slug'] ?? $product->slug);
        $siteUuid = (string) ($siteResponse['uuid'] ?? '');
        $siteSlug = (string) ($siteResponse['slug'] ?? $payloadSite['slug'] ?? '');

        if ($siteSlug === '') {
            return [];
        }

        $site = LicensedSite::query()->updateOrCreate(
            [
                'license_id' => $license->id,
                'site_slug' => $siteSlug,
            ],
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'plan_id' => $plan?->id,
                'source_project' => $sourceProject,
                'vendor_id' => isset($vendorResponse['id']) ? (int) $vendorResponse['id'] : null,
                'vendor_uuid' => isset($vendorResponse['uuid']) ? (string) $vendorResponse['uuid'] : null,
                'vendor_slug' => (string) ($vendorResponse['slug'] ?? $payloadVendor['slug'] ?? ''),
                'vendor_name' => (string) ($vendorResponse['name'] ?? $payloadVendor['name'] ?? ''),
                'site_id' => isset($siteResponse['id']) ? (int) $siteResponse['id'] : null,
                'site_uuid' => $siteUuid !== '' ? $siteUuid : null,
                'site_name' => (string) ($siteResponse['name'] ?? $payloadSite['name'] ?? $siteSlug),
                'site_domain' => isset($siteResponse['domain']) ? (string) $siteResponse['domain'] : ($payloadSite['domain'] ?? null),
                'site_license_key' => (string) ($payloadLicense['site_license_key'] ?? ''),
                'site_license_secret' => (string) ($payloadLicense['site_license_secret'] ?? ''),
                'status' => $result['status'] === 'success' ? 'active' : 'pending',
                'provisioned_at' => now(),
                'last_seen_at' => now(),
                'meta' => [
                    'provision_target' => $result['target'] ?? null,
                    'last_http_status' => $result['http_status'] ?? null,
                    'last_response' => $response,
                ],
            ]
        );

        return [
            'licensed_site_id' => $site->id,
            'site_uuid' => $site->site_uuid,
            'site_slug' => $site->site_slug,
        ];
    }
}
