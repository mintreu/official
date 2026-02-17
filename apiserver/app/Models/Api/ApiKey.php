<?php

namespace App\Models\Api;

use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * ApiKey - Customer's API credentials for accessing rate-limited APIs
 *
 * Product → has many ApiKeys (different customers)
 * License → has one ApiKey (1:1)
 * Plan → has many ApiKeys (customers on same plan)
 *
 * @property int $id
 * @property int $product_id Product this key is for
 * @property int $license_id License that granted this key
 * @property int $plan_id Plan determining rate limits
 * @property int $user_id Owner of this key
 * @property string $key_hash Hashed key for lookup
 * @property string $key_prefix First 12 chars for identification (sk_live_xxxx...)
 * @property string|null $name User-defined name
 * @property string|null $domain_restriction Restrict to specific domain
 * @property array|null $allowed_domains Allowed domains list (JSON)
 * @property string $environment dev|prod
 * @property array|null $ip_whitelist Allowed IPs (null = any)
 * @property int $requests_this_month Current month usage
 * @property int $requests_today Today's usage
 * @property \Carbon\Carbon|null $last_used_at
 * @property \Carbon\Carbon|null $expires_at
 * @property bool $is_active
 */
class ApiKey extends Model
{
    protected $fillable = [
        'product_id',
        'license_id',
        'plan_id',
        'user_id',
        'key_hash',
        'key_prefix',
        'name',
        'domain_restriction',
        'allowed_domains',
        'environment',
        'ip_whitelist',
        'requests_this_month',
        'requests_today',
        'last_used_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'ip_whitelist' => 'json',
            'allowed_domains' => 'json',
            'requests_this_month' => 'integer',
            'requests_today' => 'integer',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The actual key - only available immediately after creation
     */
    public ?string $plainKey = null;

    // ===== RELATIONSHIPS =====

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(ApiUsageLog::class);
    }

    // ===== KEY GENERATION =====

    /**
     * Generate new API key
     * Returns the plain key (only available once!)
     */
    public static function generateKey(string $prefix = 'sk_live'): array
    {
        $random = Str::random(32);
        $key = $prefix.'_'.$random;

        return [
            'key' => $key,
            'hash' => hash('sha256', $key),
            'prefix' => substr($key, 0, 12).'...',
        ];
    }

    /**
     * Find API key by plain key
     */
    public static function findByKey(string $plainKey): ?self
    {
        $hash = hash('sha256', $plainKey);

        return self::where('key_hash', $hash)->first();
    }

    // ===== VALIDATION =====

    /**
     * Check if key is valid for use
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if (! $this->license || ! $this->license->isValid()) {
            return false;
        }

        return true;
    }

    /**
     * Check if request IP is allowed
     */
    public function isIpAllowed(?string $ip): bool
    {
        if (empty($this->ip_whitelist)) {
            return true; // No restriction
        }

        return in_array($ip, $this->ip_whitelist);
    }

    /**
     * Check if request domain is allowed
     */
    public function isDomainAllowed(?string $domain): bool
    {
        $normalized = self::normalizeDomain($domain);
        $allowed = $this->getAllowedDomains();

        if (! empty($allowed)) {
            if ($normalized === null) {
                return false;
            }

            foreach ($allowed as $allowedDomain) {
                $allowedDomain = self::normalizeDomain($allowedDomain);
                if (! $allowedDomain) {
                    continue;
                }

                if ($normalized === $allowedDomain || str_ends_with($normalized, '.'.$allowedDomain)) {
                    return true;
                }
            }

            return false;
        }

        if (empty($this->domain_restriction)) {
            return true; // No restriction
        }

        if ($normalized === null) {
            return false;
        }

        $legacy = self::normalizeDomain($this->domain_restriction);
        if (! $legacy) {
            return false;
        }

        return $normalized === $legacy || str_ends_with($normalized, '.'.$legacy);
    }

    public function isDev(): bool
    {
        return $this->environment === 'dev';
    }

    public function isProd(): bool
    {
        return $this->environment === 'prod';
    }

    /**
     * Normalize domain string (host only, lowercase, no port)
     */
    public static function normalizeDomain(?string $domain): ?string
    {
        $domain = $domain ? strtolower(trim($domain)) : null;
        if ($domain === null || $domain === '') {
            return null;
        }

        $domain = preg_replace('#^https?://#', '', $domain);
        $domain = preg_replace('#/.*$#', '', $domain);
        $domain = preg_replace('#:\d+$#', '', $domain);

        return $domain ?: null;
    }

    public function getAllowedDomains(): array
    {
        if (is_array($this->allowed_domains) && count($this->allowed_domains) > 0) {
            return $this->allowed_domains;
        }

        return [];
    }

    // ===== RATE LIMITING =====

    /**
     * Check if within rate limits
     */
    public function isWithinLimits(): array
    {
        $plan = $this->plan;

        // Check monthly limit
        if ($plan->requests_per_month !== null) {
            if ($this->requests_this_month >= $plan->requests_per_month) {
                return [
                    'allowed' => false,
                    'reason' => 'Monthly limit exceeded',
                    'limit' => $plan->requests_per_month,
                    'used' => $this->requests_this_month,
                    'reset' => now()->endOfMonth()->toISOString(),
                ];
            }
        }

        // Check daily limit
        if ($plan->requests_per_day !== null) {
            if ($this->requests_today >= $plan->requests_per_day) {
                return [
                    'allowed' => false,
                    'reason' => 'Daily limit exceeded',
                    'limit' => $plan->requests_per_day,
                    'used' => $this->requests_today,
                    'reset' => now()->endOfDay()->toISOString(),
                ];
            }
        }

        return ['allowed' => true];
    }

    /**
     * Record API usage
     */
    public function recordUsage(int $weight = 1): void
    {
        $this->increment('requests_this_month', $weight);
        $this->increment('requests_today', $weight);
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Get usage stats
     */
    public function getUsageStats(): array
    {
        $plan = $this->plan;

        return [
            'monthly' => [
                'used' => $this->requests_this_month,
                'limit' => $plan->requests_per_month,
                'remaining' => $plan->requests_per_month
                    ? max(0, $plan->requests_per_month - $this->requests_this_month)
                    : null,
                'percentage' => $plan->requests_per_month
                    ? round(($this->requests_this_month / $plan->requests_per_month) * 100, 2)
                    : 0,
            ],
            'daily' => [
                'used' => $this->requests_today,
                'limit' => $plan->requests_per_day,
                'remaining' => $plan->requests_per_day
                    ? max(0, $plan->requests_per_day - $this->requests_today)
                    : null,
            ],
            'last_used' => $this->last_used_at?->toISOString(),
        ];
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ===== BOOT =====

    protected static function booted(): void
    {
        static::saving(function (ApiKey $apiKey): void {
            $limit = $apiKey->plan?->limits['domains'] ?? null;
            if ($limit !== null) {
                $count = is_array($apiKey->allowed_domains)
                    ? count($apiKey->allowed_domains)
                    : 0;
                if ($count > (int) $limit) {
                    throw ValidationException::withMessages([
                        'allowed_domains' => 'Domain limit exceeded for this plan.',
                    ]);
                }
            }

            if (empty($apiKey->environment)) {
                $apiKey->environment = 'prod';
            }
        });
    }

    // ===== RESET COUNTERS (called by scheduler) =====

    /**
     * Reset daily counters - run at midnight
     */
    public static function resetDailyCounters(): int
    {
        return self::query()->update(['requests_today' => 0]);
    }

    /**
     * Reset monthly counters - run on 1st of month
     */
    public static function resetMonthlyCounters(): int
    {
        return self::query()->update(['requests_this_month' => 0]);
    }
}
