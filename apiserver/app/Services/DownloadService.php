<?php

namespace App\Services;

use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\DownloadLog;
use App\Models\Products\ProductSource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
     *
     * For private GitHub repos, we make an authenticated API request
     * that returns a temporary S3 URL (no auth needed for redirect).
     */
    private function buildAuthenticatedUrl(ProductSource $source): string
    {
        $baseUrl = $source->source_url;

        // Get effective token (GitProviderToken > encrypted_token > config)
        $token = $source->getEffectiveToken();

        // GitHub private repo handling
        if ($source->provider->value === 'github' && $token) {
            return $this->getGitHubAuthenticatedUrl($baseUrl, $token, $source);
        }

        // GitLab private repo handling
        if ($source->provider->value === 'gitlab' && $token) {
            return $this->getGitLabAuthenticatedUrl($baseUrl, $token);
        }

        return $baseUrl;
    }

    /**
     * Get authenticated download URL for GitHub private repos
     *
     * GitHub's API returns a 302 redirect to a temporary pre-signed S3 URL
     * that doesn't require authentication. We follow the redirect to get that URL.
     */
    private function getGitHubAuthenticatedUrl(string $baseUrl, string $token, ProductSource $source): string
    {
        // Build API URL from github.com URL or use as-is if already API URL
        $apiUrl = $this->convertToGitHubApiUrl($baseUrl, $source);

        if (! $apiUrl) {
            Log::warning('Could not convert to GitHub API URL', ['url' => $baseUrl]);

            return $baseUrl;
        }

        try {
            // Make request but don't follow redirects - we want the redirect URL
            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/vnd.github+json',
                    'X-GitHub-Api-Version' => '2022-11-28',
                ])
                ->withOptions(['allow_redirects' => false])
                ->get($apiUrl);

            // GitHub returns 302 with Location header pointing to S3
            if ($response->status() === 302) {
                $redirectUrl = $response->header('Location');
                if ($redirectUrl) {
                    Log::info('GitHub redirect URL obtained', ['url' => substr($redirectUrl, 0, 100).'...']);

                    return $redirectUrl;
                }
            }

            // If we got a 200, GitHub returned the content directly (shouldn't happen for archives)
            if ($response->successful()) {
                Log::warning('GitHub returned 200 instead of redirect', ['status' => $response->status()]);

                return $baseUrl;
            }

            Log::warning('GitHub API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('GitHub authenticated URL request failed', [
                'error' => $e->getMessage(),
                'url' => $apiUrl,
            ]);
        }

        return $baseUrl;
    }

    /**
     * Convert github.com URLs to API URLs
     */
    private function convertToGitHubApiUrl(string $url, ProductSource $source): ?string
    {
        // Already an API URL
        if (str_contains($url, 'api.github.com')) {
            return $url;
        }

        // Get owner/repo from metadata or parse URL
        $metadata = $source->metadata ?? [];
        $owner = $metadata['owner'] ?? null;
        $repo = $metadata['repo'] ?? null;

        // Parse from URL if not in metadata
        if (! $owner || ! $repo) {
            if (preg_match('#github\.com/([^/]+)/([^/]+)#', $url, $matches)) {
                $owner = $matches[1];
                $repo = rtrim($matches[2], '.git');
            }
        }

        if (! $owner || ! $repo) {
            return null;
        }

        // Archive URL: https://github.com/owner/repo/archive/refs/tags/v1.zip
        if (preg_match('#/archive/refs/(heads|tags)/([^/]+)\.zip#', $url, $matches)) {
            $ref = $matches[2];

            return "https://api.github.com/repos/{$owner}/{$repo}/zipball/{$ref}";
        }

        // Zipball URL from API
        if (preg_match('#/zipball/(.+)$#', $url, $matches)) {
            $ref = $matches[1];

            return "https://api.github.com/repos/{$owner}/{$repo}/zipball/{$ref}";
        }

        // Release asset URL: https://github.com/owner/repo/releases/download/v1/file.zip
        if (preg_match('#/releases/download/([^/]+)/(.+)$#', $url, $matches)) {
            // For release assets, we need to find the asset ID
            // This is more complex - for now, return the direct URL
            return $url;
        }

        // Fallback: use version from source as ref
        $ref = $source->version ?? 'main';

        return "https://api.github.com/repos/{$owner}/{$repo}/zipball/{$ref}";
    }

    /**
     * Get authenticated download URL for GitLab private repos
     */
    private function getGitLabAuthenticatedUrl(string $baseUrl, string $token): string
    {
        // GitLab allows token as query parameter
        $separator = str_contains($baseUrl, '?') ? '&' : '?';

        return $baseUrl.$separator.'private_token='.$token;
    }

    /**
     * Get provider token from config
     */
    private function getProviderToken(string $provider): ?string
    {
        return match ($provider) {
            'github' => config('services.github.token'),
            'gitlab' => config('services.gitlab.token'),
            'bitbucket' => config('services.bitbucket.password'),
            default => null,
        };
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
