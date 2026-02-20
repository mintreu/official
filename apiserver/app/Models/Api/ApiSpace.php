<?php

namespace App\Models\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiSpace extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'api_key_id',
        'product_id',
        'name',
        'website',
        'environment',
        'status',
        'requests_this_month',
        'requests_today',
        'last_request_at',
        'config',
        'insights',
    ];

    protected function casts(): array
    {
        return [
            'requests_this_month' => 'integer',
            'requests_today' => 'integer',
            'last_request_at' => 'datetime',
            'config' => 'json',
            'insights' => 'json',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $space): void {
            if (empty($space->uuid)) {
                $space->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
