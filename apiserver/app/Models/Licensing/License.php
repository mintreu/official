<?php

namespace App\Models\Licensing;

use App\Enums\LicenseType;
use App\Models\Api\ApiKey;
use App\Models\Product;
use App\Models\Products\DownloadLog;
use App\Models\Products\Plan;
use App\Models\Saas\LicensedSite;
use App\Models\User;
use App\Traits\HasTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * License Model - Product license/subscription for users
 *
 * @property int $id
 * @property string $uuid
 * @property int $product_id
 * @property int|null $plan_id Plan purchased (for API products)
 * @property int|null $user_id
 * @property string $license_key Unique license key
 * @property LicenseType $type License type enum
 * @property string|null $email License holder email (if no user_id)
 * @property int $usage_count Current usage count (for downloads)
 * @property int|null $max_usage Maximum allowed usages (null = unlimited)
 * @property array|null $meta Additional license data (JSON)
 * @property array|null $server_info SDK-reported server info (JSON)
 * @property \Carbon\Carbon|null $expires_at Expiration date
 * @property bool $is_active License active status
 * @property \Carbon\Carbon|null $first_used_at First usage timestamp
 * @property \Carbon\Carbon|null $last_used_at Last usage timestamp
 */
class License extends Model
{
    use HasFactory;
    use HasTransaction;

    protected static function newFactory(): \Database\Factories\LicenseFactory
    {
        return \Database\Factories\LicenseFactory::new();
    }

    protected $fillable = [
        'uuid',
        'product_id',
        'plan_id',
        'user_id',
        'license_key',
        'type',
        'email',
        'usage_count',
        'max_usage',
        'meta',
        'server_info',
        'expires_at',
        'is_active',
        'first_used_at',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => LicenseType::class,
            'meta' => 'json',
            'server_info' => 'json',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'first_used_at' => 'datetime',
            'last_used_at' => 'datetime',
            'usage_count' => 'integer',
            'max_usage' => 'integer',
        ];
    }

    // ===== RELATIONSHIPS =====

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    /**
     * API key for this license (for API products)
     */
    public function apiKey(): HasOne
    {
        return $this->hasOne(ApiKey::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(LicensedSite::class);
    }

    // ===== VALIDATION =====

    /**
     * Check if license is valid and can be used
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        if ($this->isUsageLimitReached()) {
            return false;
        }

        return true;
    }

    /**
     * Check if license has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if usage limit reached
     */
    public function isUsageLimitReached(): bool
    {
        return $this->max_usage !== null && $this->usage_count >= $this->max_usage;
    }

    /**
     * Get remaining usages
     */
    public function getRemainingUsages(): ?int
    {
        if ($this->max_usage === null) {
            return null; // unlimited
        }

        return max(0, $this->max_usage - $this->usage_count);
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpiration(): ?int
    {
        if (! $this->expires_at) {
            return null; // never expires
        }

        return max(0, now()->diffInDays($this->expires_at, false));
    }

    // ===== ACTIONS =====

    /**
     * Record usage and check limits
     */
    public function recordUsage(): bool
    {
        if (! $this->isValid()) {
            return false;
        }

        $this->increment('usage_count');

        $updates = ['last_used_at' => now()];
        if ($this->first_used_at === null) {
            $updates['first_used_at'] = now();
        }

        $this->update($updates);

        return true;
    }

    /**
     * Deactivate license
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Extend license expiration
     */
    public function extend(int $days): void
    {
        $baseDate = $this->expires_at && $this->expires_at->isFuture()
            ? $this->expires_at
            : now();

        $this->update(['expires_at' => $baseDate->addDays($days)]);
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    // ===== BOOT =====

    protected static function booted(): void
    {
        static::creating(function (License $license) {
            if (empty($license->uuid)) {
                $license->uuid = (string) Str::uuid();
            }

            if (empty($license->license_key)) {
                $license->license_key = self::generateKey();
            }

            // Set max_usage from license type if not specified
            if ($license->max_usage === null && $license->type) {
                $license->max_usage = $license->type->maxUsages();
            }

            // Set expiration from license type if not specified
            if ($license->expires_at === null && $license->type?->expiresInDays()) {
                $license->expires_at = now()->addDays($license->type->expiresInDays());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Generate unique license key
     */
    public static function generateKey(): string
    {
        // Format: XXXX-XXXX-XXXX-XXXX (uppercase alphanumeric)
        $segments = [];
        for ($i = 0; $i < 4; $i++) {
            $segments[] = strtoupper(Str::random(4));
        }

        return implode('-', $segments);
    }

    protected function resolveTransactionAmount(): int
    {
        return (int) ($this->plan?->price_cents ?? 0);
    }
}
