<?php

namespace App\Models\Api;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ApiEndpoint - Defines an API route for a product
 *
 * For API products, you define which endpoints exist
 * SDK uses this to verify requests and apply rate limits
 *
 * @property int $id
 * @property int $product_id
 * @property string $method HTTP method (GET, POST, PUT, DELETE)
 * @property string $path Endpoint path pattern (e.g., "/users/{id}")
 * @property string $name Human readable name
 * @property string|null $description
 * @property string|null $base_url Where API is actually hosted (for proxy/verification)
 * @property int $weight Request weight for rate limiting (default 1)
 * @property bool $is_public Accessible without API key
 * @property bool $is_active Endpoint enabled
 * @property array|null $params Expected parameters schema
 * @property array|null $response Example response schema
 */
class ApiEndpoint extends Model
{
    protected $fillable = [
        'product_id',
        'method',
        'path',
        'name',
        'description',
        'base_url',
        'weight',
        'is_public',
        'is_active',
        'params',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'integer',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
            'params' => 'json',
            'response' => 'json',
        ];
    }

    // ===== RELATIONSHIPS =====

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(ApiUsageLog::class);
    }

    // ===== HELPERS =====

    /**
     * Get full path with method for display
     */
    public function getFullPath(): string
    {
        return strtoupper($this->method).' '.$this->path;
    }

    /**
     * Check if path matches a given request path
     */
    public function matchesPath(string $requestPath): bool
    {
        // Convert {param} to regex pattern
        $pattern = preg_replace('/\{[^}]+\}/', '[^/]+', $this->path);
        $pattern = '#^'.$pattern.'$#';

        return (bool) preg_match($pattern, $requestPath);
    }

    /**
     * Build actual URL for proxy/forwarding
     */
    public function buildActualUrl(string $requestPath, array $query = []): ?string
    {
        if (! $this->base_url) {
            return null;
        }

        $url = rtrim($this->base_url, '/').$requestPath;

        if (! empty($query)) {
            $url .= '?'.http_build_query($query);
        }

        return $url;
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeProtected($query)
    {
        return $query->where('is_public', false);
    }
}
