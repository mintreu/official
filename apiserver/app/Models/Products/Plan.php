<?php

namespace App\Models\Products;

use App\Models\Api\ApiKey;
use App\Models\Licensing\License;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Plan - Pricing tier for a product
 *
 * Each product can have multiple plans (Free, Basic, Pro, Enterprise)
 * Plans define: price, billing cycle, rate limits, features
 *
 * @property int $id
 * @property int $product_id
 * @property string $slug Unique within product (e.g., "basic", "pro")
 * @property string $name Display name
 * @property string|null $description
 * @property int $price_cents Price in cents (0 = free)
 * @property string $billing_cycle monthly, yearly, lifetime, one_time
 * @property int|null $requests_per_month API rate limit (null = unlimited)
 * @property int|null $requests_per_day Daily limit (null = use monthly)
 * @property int|null $requests_per_minute Burst limit
 * @property array|null $features List of features included
 * @property array|null $limits Additional limits (storage, seats, etc.)
 * @property int $sort_order Display order
 * @property bool $is_popular Highlight this plan
 * @property bool $is_active Plan available for purchase
 */
class Plan extends Model
{
    protected $fillable = [
        'product_id',
        'slug',
        'name',
        'description',
        'price_cents',
        'billing_cycle',
        'requests_per_month',
        'requests_per_day',
        'requests_per_minute',
        'features',
        'limits',
        'sort_order',
        'is_popular',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'requests_per_month' => 'integer',
            'requests_per_day' => 'integer',
            'requests_per_minute' => 'integer',
            'features' => 'json',
            'limits' => 'json',
            'sort_order' => 'integer',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // ===== RELATIONSHIPS =====

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    // ===== HELPERS =====

    /**
     * Get price in dollars
     */
    public function getPriceAttribute(): float
    {
        return $this->price_cents / 100;
    }

    /**
     * Format price for display
     */
    public function getFormattedPrice(): string
    {
        if ($this->price_cents === 0) {
            return 'Free';
        }

        return '$'.number_format($this->price / 100, 2);
    }

    /**
     * Check if plan is free
     */
    public function isFree(): bool
    {
        return $this->price_cents === 0;
    }

    /**
     * Check if plan has rate limits
     */
    public function hasRateLimits(): bool
    {
        return $this->requests_per_month !== null
            || $this->requests_per_day !== null
            || $this->requests_per_minute !== null;
    }

    /**
     * Get billing cycle label
     */
    public function getBillingLabel(): string
    {
        return match ($this->billing_cycle) {
            'monthly' => '/month',
            'yearly' => '/year',
            'lifetime' => 'one-time',
            'one_time' => 'one-time',
            default => '',
        };
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price_cents');
    }
}
