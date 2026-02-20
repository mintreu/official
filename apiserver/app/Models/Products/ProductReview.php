<?php

declare(strict_types=1);

namespace App\Models\Products;

use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductReview extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'product_id',
        'license_id',
        'rating',
        'review',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $review): void {
            if (empty($review->uuid)) {
                $review->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
