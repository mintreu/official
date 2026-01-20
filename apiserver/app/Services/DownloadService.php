<?php

namespace App\Services;

use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\DownloadLog;
use App\Models\Products\ProductSource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * DownloadService - Secure download URL generation and handling
 *
 * Creates temporary masked URLs that:
 * 1. Hide real source URLs from users
 * 2. Redirect to actual download without proxying (no bandwidth cost)
 * 3. Track download analytics
 * 4. Validate license/permissions before allowing download
 */
class DownloadService
{
    /**
     * Token expiration in minutes
     */
    private const TOKEN_TTL = 30;

    /**
     * Cache prefix for download tokens
     */
    private const CACHE_PREFIX = 'download_token:';

    /**
     * Generate a secure masked download URL
     *
     * @param  ProductSource  $source  The source to download
     * @param  User|null  $user  Authenticated user (if any)
     * @param  License|null  $license  License being used (if any)
     * @return array{url: string, token: string, expires_at: string}
     */
    public function generateDownloadUrl(
        ProductSource $source,
        ?User $user = null,
        ?License $license = null
    ): array {
        // Generate unique token: ULID + timestamp hash
        $token = $this->generateToken();

        // Store token data in cache
        $tokenData = [
            'source_id' => $source->id,
            'product_id' => $source->product_id,
            'user_id' => $user?->id,
            'license_id' => $license?->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()->toISOString(),
        ];

        Cache::put(
            self::CACHE_PREFIX.$token,
            $tokenData,
            now()->addMinutes(self::TOKEN_TTL)
        );

        $expiresAt = now()->addMinutes(self::TOKEN_TTL);

        return [
            'url' => route('api.download', ['token' => $token]),
            'token' => $token,
            'expires_at' => $expiresAt->toISOString(),
        ];
    }

    /**
     * Validate token and get real download URL
     *
     * @param  string  $token  The download token
     * @return array{source: ProductSource, redirect_url: string}|null
     */
    public function validateAndGetDownloadUrl(string $token): ?array
    {
        $cacheKey = self::CACHE_PREFIX.$token;
        $tokenData = Cache::get($cacheKey);

        if (! $tokenData) {
            return null;
        }

        // Basic security check - IP should match
        if ($tokenData['ip'] !== request()->ip()) {
            return null;
        }

        $source = ProductSource::find($tokenData['source_id']);

        if (! $source || ! $source->is_active) {
            return null;
        }

        // Log the download
        $this->logDownload($source, $tokenData, $token);

        // Invalidate token (one-time use)
        Cache::forget($cacheKey);

        // Get real URL with auth if needed
        $redirectUrl = $this->buildAuthenticatedUrl($source);

        return [
            'source' => $source,
            'redirect_url' => $redirectUrl,
        ];
    }

    /**
     * Generate secure token
     */
    private function generateToken(): string
    {
        // ULID for sortability + random suffix
        $ulid = Str::ulid();
        $suffix = Str::random(8);

        // Base64 encode for URL safety
        return base64_encode($ulid.':'.$suffix);
    }

    /**
     * Build authenticated download URL for private sources
     */
    private function buildAuthenticatedUrl(ProductSource $source): string
    {
        $baseUrl = $source->source_url;

        // For GitHub/GitLab/etc, append auth token if available
        if ($source->encrypted_token) {
            $token = $source->getToken();

            // GitHub private repo format
            if ($source->provider->value === 'github') {
                // GitHub accepts token in URL for releases
                // Format: https://api.github.com/repos/owner/repo/releases/assets/{id}
                // with Authorization header, but for direct download we use:
                if (str_contains($baseUrl, 'github.com')) {
                    return $baseUrl.(str_contains($baseUrl, '?') ? '&' : '?').'token='.$token;
                }
            }

            // For other providers, token might be used differently
            // S3 presigned URLs, GDrive tokens, etc.
            // Store the full presigned URL or generate it here based on provider
        }

        return $baseUrl;
    }

    /**
     * Log download activity
     */
    private function logDownload(ProductSource $source, array $tokenData, string $token): void
    {
        DownloadLog::create([
            'product_id' => $source->product_id,
            'product_source_id' => $source->id,
            'license_id' => $tokenData['license_id'],
            'user_id' => $tokenData['user_id'],
            'ip_address' => $tokenData['ip'],
            'user_agent' => $tokenData['user_agent'],
            'status' => 'completed',
            'download_token' => substr($token, 0, 32), // Store partial for reference
            'file_size' => $source->file_size,
            'downloaded_at' => now(),
        ]);

        // Increment product download counter
        Product::where('id', $source->product_id)->increment('downloads');

        // Record license usage if applicable
        if ($tokenData['license_id']) {
            License::find($tokenData['license_id'])?->recordUsage();
        }
    }

    /**
     * Check if user can download this product
     *
     * @return array{allowed: bool, reason?: string, license?: License}
     */
    public function canDownload(Product $product, ?User $user): array
    {
        // Freebies - always allowed
        if ($product->type->showsPublicSource()) {
            return ['allowed' => true];
        }

        // Check if auth required
        if ($product->requires_auth && ! $user) {
            return [
                'allowed' => false,
                'reason' => 'Login required to download this product',
            ];
        }

        // Free products without auth requirement
        if ($product->isFree() && ! $product->requires_auth) {
            return ['allowed' => true];
        }

        // Paid products - check license
        if ($user) {
            $license = $product->licenses()
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if ($license && $license->isValid()) {
                return [
                    'allowed' => true,
                    'license' => $license,
                ];
            }
        }

        // No valid license
        if (! $product->isFree()) {
            return [
                'allowed' => false,
                'reason' => 'Purchase required to download this product',
            ];
        }

        return ['allowed' => true];
    }
}
