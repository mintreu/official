<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tracks downloads/rating/version per product.
 */
class ProductEngagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'downloads',
        'rating',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'downloads' => 'integer',
            'rating' => 'float',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
