<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'action_type',
        'ip_address',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'json',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function recordView(Product $product, ?User $user = null, string $ipAddress = ''): void
    {
        self::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'action_type' => 'view',
            'ip_address' => $ipAddress,
        ]);

        $product->increment('downloads');
    }

    public static function recordDownload(Product $product, ?User $user = null, string $ipAddress = ''): void
    {
        self::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'action_type' => 'download',
            'ip_address' => $ipAddress,
        ]);
    }

    public static function recordShare(Product $product, ?User $user = null, string $platform = '', string $ipAddress = ''): void
    {
        self::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'action_type' => 'share',
            'ip_address' => $ipAddress,
            'metadata' => [
                'platform' => $platform,
            ],
        ]);
    }

    public static function recordClick(Product $product, ?User $user = null, string $clickType = '', string $ipAddress = ''): void
    {
        self::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'action_type' => 'click',
            'ip_address' => $ipAddress,
            'metadata' => [
                'click_type' => $clickType,
            ],
        ]);
    }
}
