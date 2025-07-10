<?php

namespace App\Models\Subscription;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'desc',
        'base_price',
        'hsn_code',
        'tax_percent',
        'tax_amount',
        'price',
        'per_month_limit',
        'auth_type',
        'support_type',
        'documentation_type',
        'features',
        'is_recommended',
        'is_enterprise',
        'visible_on_front',
        'product_id',
    ];

    protected $casts = [
        'features' => 'array',
        'is_recommended' => 'boolean',
        'is_enterprise' => 'boolean',
        'visible_on_front' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
