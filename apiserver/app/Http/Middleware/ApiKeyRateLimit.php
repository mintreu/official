<?php

namespace App\Http\Middleware;

use App\Models\Api\ApiKey;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyRateLimit
{
    private const MINUTE_WINDOW_SECONDS = 60;

    /**
     * Enforce per-key rate limits based on plan settings.
     *
     * Configured in plans table:
     * - requests_per_minute
     * - requests_per_day
     * - requests_per_month
     */
    public function handle(Request $request, Closure $next): Response
    {
        $plainKey = $this->extractApiKey($request);

        if (! $plainKey) {
            return $this->unauthorized('API key missing');
        }

        $apiKey = ApiKey::query()
            ->where('key_hash', hash('sha256', $plainKey))
            ->with(['plan', 'license'])
            ->first();

        if (! $apiKey || ! $apiKey->isValid()) {
            return $this->unauthorized('Invalid API key');
        }

        if (! $apiKey->isIpAllowed($request->ip())) {
            return response()->json([
                'message' => 'IP not allowed',
            ], 403);
        }

        $domain = $this->resolveRequestDomain($request);
        $normalizedDomain = ApiKey::normalizeDomain($domain);

        if ($apiKey->isProd() && empty($apiKey->allowed_domains) && empty($apiKey->domain_restriction)) {
            return response()->json([
                'message' => 'Domain not allowed',
            ], 403);
        }

        if ($apiKey->isDev() && $this->isLocalhostDomain($normalizedDomain)) {
            // Allow localhost for dev keys even if not explicitly listed.
        } elseif (! $apiKey->isDomainAllowed($normalizedDomain)) {
            return response()->json([
                'message' => 'Domain not allowed',
            ], 403);
        }

        $limitCheck = $apiKey->isWithinLimits();
        if (! $limitCheck['allowed']) {
            return $this->rateLimitExceeded($limitCheck['reset'] ?? null);
        }

        $plan = $apiKey->plan;
        if ($plan && $plan->requests_per_minute !== null) {
            $rateKey = "api:{$apiKey->id}:minute";

            if (RateLimiter::tooManyAttempts($rateKey, $plan->requests_per_minute)) {
                return $this->rateLimitExceeded(
                    now()->addSeconds(RateLimiter::availableIn($rateKey))->toISOString(),
                    RateLimiter::availableIn($rateKey)
                );
            }

            RateLimiter::hit($rateKey, self::MINUTE_WINDOW_SECONDS);
        }

        $apiKey->recordUsage();
        $request->attributes->set('apiKey', $apiKey);

        return $next($request);
    }

    private function extractApiKey(Request $request): ?string
    {
        $authorization = $request->header('Authorization');
        if ($authorization && preg_match('/^Bearer\s+(.*)$/i', $authorization, $matches)) {
            return trim($matches[1]);
        }

        $headerKey = $request->header('X-API-KEY');
        if ($headerKey) {
            return trim($headerKey);
        }

        return null;
    }

    private function resolveRequestDomain(Request $request): ?string
    {
        $origin = $request->header('Origin') ?? $request->header('Referer');
        if ($origin) {
            $host = parse_url($origin, PHP_URL_HOST);
            if ($host) {
                return $host;
            }
        }

        return null;
    }

    private function isLocalhostDomain(?string $domain): bool
    {
        return $domain === 'localhost' || $domain === '127.0.0.1';
    }

    private function rateLimitExceeded(?string $resetAt = null, ?int $retryAfterSeconds = null): JsonResponse
    {
        $retryAfter = $retryAfterSeconds ?? ($resetAt ? max(1, now()->diffInSeconds($resetAt)) : 60);

        return response()->json([
            'message' => 'Rate limit exceeded',
            'retry_after' => $retryAfter,
        ], 429);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 401);
    }
}
