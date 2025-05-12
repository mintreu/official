<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPlan extends Model
{
    /** @use HasFactory<\Database\Factories\Product/ProductPlanFactory> */
    use HasFactory;



    protected $fillable = [
        'product_id',
        'name',
        'desc',
        'price',
        'per_month_limit',
        'records_limit',
        'is_recommended',
        'is_enterprise',
        'visible_on_front',
        'has_support',
    ];


    protected $casts = [
        'price' => 'float',
        'per_month_limit' => 'integer',
        'records_limit' => 'integer',
        'is_recommended' => 'boolean',
        'is_enterprise' => 'boolean',
        'visible_on_front' => 'boolean',
        'has_support' => 'boolean',
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }










}
