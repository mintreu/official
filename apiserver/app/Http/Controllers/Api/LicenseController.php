<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Licensing\License;
use App\Models\Products\Product;
use App\Models\Products\ProductSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LicenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $licenses = $user->licenses()
            ->with([
                'product',
                'product.engagement',
                'product.activeSources',
                'plan',
                'apiKey.plan',
            ])
            ->orderByDesc('created_at')
            ->get();

        $payload = $licenses->map(fn (License $license) => $this->mapLicense($license));

        return response()->json([
            'data' => $payload,
            'feature_flags' => $this->featureFlags(),
        ]);
    }

    private function mapLicense(License $license): array
    {
        $product = $license->product;
        $plan = $license->plan;
        $apiKey = $license->apiKey;

        return [
            'id' => $license->id,
            'license_key' => $license->license_key,
            'type' => $license->type->value,
            'type_label' => $license->type->getLabel(),
            'status' => $this->determineStatus($license),
            'is_active' => $license->is_active,
            'usage_count' => $license->usage_count,
            'max_usage' => $license->max_usage,
            'remaining_usages' => $license->getRemainingUsages(),
            'expires_at' => $license->expires_at?->toIsoString(),
            'created_at' => $license->created_at?->toIsoString(),
            'next_renewal_at' => $license->expires_at?->toIsoString(),
            'product' => $this->mapProduct($product),
            'plan' => $plan ? $this->mapPlan($plan) : null,
            'download_sources' => $this->mapSources($product->activeSources),
            'api_key' => $apiKey ? $this->mapApiKey($apiKey) : null,
        ];
    }

    private function determineStatus(License $license): string
    {
        if (! $license->is_active) {
            return 'disabled';
        }

        if ($license->isExpired()) {
            return 'expired';
        }

        if ($license->isUsageLimitReached()) {
            return 'usage_limit';
        }

        return 'active';
    }

    private function mapProduct(Product $product): array
    {
        $engagement = $product->engagement;

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $product->title,
            'short_description' => $product->short_description,
            'category' => $product->category,
            'type' => $product->type->value,
            'image' => $product->image,
            'version' => $product->version,
            'price' => (float) $product->price,
            'requires_auth' => $product->requires_auth,
            'demo_url' => $product->demo_url,
            'documentation_url' => $product->documentation_url,
            'engagement' => [
                'downloads' => $engagement?->downloads ?? $product->downloads,
                'rating' => $engagement?->rating ?? $product->rating,
            ],
        ];
    }

    private function mapPlan($plan): array
    {
        return [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'description' => $plan->description,
            'price' => $plan->price,
            'price_formatted' => $plan->getFormattedPrice(),
            'billing_cycle' => $plan->billing_cycle,
            'features' => $plan->features,
            'limits' => $plan->limits,
            'requests_per_month' => $plan->requests_per_month,
            'requests_per_day' => $plan->requests_per_day,
            'requests_per_minute' => $plan->requests_per_minute,
            'is_popular' => $plan->is_popular,
        ];
    }

    private function mapSources(Collection $sources): array
    {
        return $sources->map(fn (ProductSource $source) => [
            'id' => $source->id,
            'name' => $source->name,
            'description' => $source->description,
            'version' => $source->version,
            'file_name' => $source->file_name,
            'file_size' => $source->file_size,
            'file_size_formatted' => $source->getFileSizeFormatted(),
            'is_primary' => $source->is_primary,
            'provider' => $source->provider->value,
            'provider_label' => $source->provider->getLabel(),
        ])->toArray();
    }

    private function mapApiKey($apiKey): array
    {
        return [
            'id' => $apiKey->id,
            'key_prefix' => $apiKey->key_prefix,
            'is_active' => $apiKey->is_active,
            'requests_this_month' => $apiKey->requests_this_month,
            'requests_today' => $apiKey->requests_today,
            'last_used_at' => $apiKey->last_used_at?->toIsoString(),
            'expires_at' => $apiKey->expires_at?->toIsoString(),
            'environment' => $apiKey->environment,
            'plan' => $apiKey->plan ? [
                'requests_per_month' => $apiKey->plan->requests_per_month,
                'requests_per_day' => $apiKey->plan->requests_per_day,
            ] : null,
        ];
    }

    private function featureFlags(): array
    {
        return [
            'downloads_enabled' => true,
            'api_access_enabled' => true,
            'licensing_enabled' => true,
        ];
    }
}
