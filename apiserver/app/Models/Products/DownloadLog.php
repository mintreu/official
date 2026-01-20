<?php

namespace App\Models\Products;

use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DownloadLog - Tracks all download activity
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $product_source_id Which source was downloaded
 * @property int|null $license_id License used (if any)
 * @property int|null $user_id Authenticated user (if any)
 * @property string|null $ip_address Requester IP
 * @property string|null $user_agent Browser/client info
 * @property string $status Download status (pending, completed, failed)
 * @property string|null $download_token Token used (partial, for reference)
 * @property int|null $file_size Downloaded file size
 * @property \Carbon\Carbon|null $downloaded_at When download completed
 */
class DownloadLog extends Model
{
    protected $fillable = [
        'product_id',
        'product_source_id',
        'license_id',
        'user_id',
        'ip_address',
        'user_agent',
        'status',
        'download_token',
        'file_size',
        'downloaded_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'downloaded_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productSource(): BelongsTo
    {
        return $this->belongsTo(ProductSource::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('downloaded_at', today());
    }

    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }
}
