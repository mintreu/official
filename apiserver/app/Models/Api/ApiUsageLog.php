<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ApiUsageLog - Tracks every API request for analytics and debugging
 *
 * @property int $id
 * @property int $api_key_id
 * @property int|null $api_endpoint_id
 * @property string $method HTTP method
 * @property string $path Request path
 * @property int $status_code Response status
 * @property int $response_time_ms Response time in milliseconds
 * @property int $request_size Request size in bytes
 * @property int $response_size Response size in bytes
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $referer
 * @property string|null $country GeoIP country code
 * @property array|null $request_headers Relevant headers (not sensitive)
 * @property array|null $error_details If request failed
 * @property \Carbon\Carbon $created_at
 */
class ApiUsageLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'api_key_id',
        'api_endpoint_id',
        'method',
        'path',
        'status_code',
        'response_time_ms',
        'request_size',
        'response_size',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'request_headers',
        'error_details',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'response_time_ms' => 'integer',
            'request_size' => 'integer',
            'response_size' => 'integer',
            'request_headers' => 'json',
            'error_details' => 'json',
            'created_at' => 'datetime',
        ];
    }

    // ===== RELATIONSHIPS =====

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }

    public function apiEndpoint(): BelongsTo
    {
        return $this->belongsTo(ApiEndpoint::class);
    }

    // ===== HELPERS =====

    /**
     * Check if request was successful
     */
    public function isSuccess(): bool
    {
        return $this->status_code >= 200 && $this->status_code < 300;
    }

    /**
     * Check if request was rate limited
     */
    public function wasRateLimited(): bool
    {
        return $this->status_code === 429;
    }

    // ===== SCOPES =====

    public function scopeForKey($query, int $apiKeyId)
    {
        return $query->where('api_key_id', $apiKeyId);
    }

    public function scopeSuccessful($query)
    {
        return $query->whereBetween('status_code', [200, 299]);
    }

    public function scopeFailed($query)
    {
        return $query->where('status_code', '>=', 400);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    // ===== BOOT =====

    protected static function booted(): void
    {
        static::creating(function (ApiUsageLog $log) {
            $log->created_at = $log->created_at ?? now();
        });
    }
}
