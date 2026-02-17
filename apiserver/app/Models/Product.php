<?php

namespace App\Models;

use App\Casts\PublishableStatusCast;
use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Models\Api\ApiEndpoint;
use App\Models\Licensing\License;
use App\Models\Products\DownloadLog;
use App\Models\Products\Plan;
use App\Models\Products\ProductSource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

/**
 * Product Model - Core product entity
 *
 * CARD VIEW: Basic columns (title, description, image, price, type, category)
 * DETAIL VIEW: Load with sources + plans JSON for full page
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $short_description For card view (max 160 chars)
 * @property string|null $description Full description
 * @property string|null $content Rich content (markdown/HTML)
 * @property string|null $image Cover image URL
 * @property string $price Decimal price (0 = free)
 * @property string|null $category Product category
 * @property ProductType $type Product type (determines frontend behavior)
 * @property string|null $demo_url Demo/preview URL
 * @property string|null $github_url Public repo URL (for freebies backlink)
 * @property string|null $documentation_url Docs URL
 * @property string|null $version Current version
 * @property int $downloads Download counter
 * @property float $rating Average rating
 * @property bool $featured Featured product flag
 * @property bool $requires_auth Require login to download
 * @property LicenseType $default_license Default license for free downloads
 * @property array|null $meta Additional metadata (JSON)
 * @property array|null $plans Pricing plans for API products (JSON)
 * @property array|null $api_config API-specific config (JSON)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'short_description',
        'description',
        'content',
        'image',
        'price',
        'type',
        'demo_url',
        'github_url',
        'documentation_url',
        'version',
        'downloads',
        'rating',
        'status',
        'featured',
        'requires_auth',
        'default_license',
        'meta',
        'api_config',
    ];

    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'default_license' => LicenseType::class,
            'status' => PublishableStatusCast::class,
            'price' => 'decimal:2',
            'downloads' => 'integer',
            'rating' => 'float',
            'featured' => 'boolean',
            'requires_auth' => 'boolean',
            'meta' => 'json',
            'api_config' => 'json',
        ];
    }

    // ===== RELATIONSHIPS =====

    /**
     * Product download sources (for downloadable products)
     */
    public function sources(): HasMany
    {
        return $this->hasMany(ProductSource::class);
    }

    /**
     * Active sources ordered by primary first
     */
    public function activeSources(): HasMany
    {
        return $this->sources()->active()->ordered();
    }

    /**
     * Pricing plans for this product
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * Active plans ordered for display
     */
    public function activePlans(): HasMany
    {
        return $this->plans()->active()->ordered();
    }

    /**
     * API endpoints (for API products)
     */
    public function apiEndpoints(): HasMany
    {
        return $this->hasMany(ApiEndpoint::class);
    }

    /**
     * API keys issued for this product (for API products)
     */
    public function apiKeys(): HasMany
    {
        return $this->hasMany(\App\Models\Api\ApiKey::class);
    }

    /**
     * Product licenses issued
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    /**
     * Download activity logs
     */
    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    /**
     * Categories (via categoryable pivot)
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
    
    public function engagement(): HasOne
    {
        return $this->hasOne(\App\Models\Products\ProductEngagement::class);
    }

    public function getCategoriesNamesAttribute(): array
    {
        return $this->categories->pluck('name')->toArray();
    }

    // ===== COMPUTED PROPERTIES =====

    /**
     * Get primary download source
     */
    public function getPrimarySource(): ?ProductSource
    {
        return $this->sources()->where('is_primary', true)->first()
            ?? $this->sources()->active()->first();
    }

    /**
     * Check if product is free
     */
    public function isFree(): bool
    {
        return $this->price <= 0 || $this->type === ProductType::Freebie;
    }

    /**
     * Check if product has downloadable files
     */
    public function isDownloadable(): bool
    {
        return $this->type->usesSecureDownload();
    }

    /**
     * Check if product has pricing plans
     */
    public function hasPlans(): bool
    {
        return $this->plans()->active()->exists();
    }

    /**
     * Check if product is an API product
     */
    public function isApiProduct(): bool
    {
        return in_array($this->type, [ProductType::ApiService, ProductType::ApiReferral]);
    }

    /**
     * Get the free plan if exists
     */
    public function getFreePlan(): ?Plan
    {
        return $this->plans()->active()->where('price_cents', 0)->first();
    }

    /**
     * Get public GitHub URL (only for freebies)
     */
    public function getPublicSourceUrl(): ?string
    {
        if (! $this->type->showsPublicSource()) {
            return null;
        }

        return $this->github_url;
    }

    // ===== SCOPES =====

    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeFree($query)
    {
        return $query->where(function ($q) {
            $q->where('price', 0)
                ->orWhere('type', ProductType::Freebie);
        });
    }

    public function scopeOfType($query, ProductType $type)
    {
        return $query->where('type', $type);
    }

    // ===== BOOT =====


}
