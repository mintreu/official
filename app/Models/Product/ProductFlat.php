<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFlat extends Model
{
    /** @use HasFactory<\Database\Factories\Product/ProductFlatFactory> */
    use HasFactory;


    protected $fillable = [
        'product_id',
        'short_desc',
        'desc',
        'host_url',
        'host_api_url',
        'client_login_url',
        'demo_accounts',
        'product_info',
        'metadata',
    ];


    protected $casts = [
        'demo_accounts' => 'array',
        'product_info' => 'array',
        'metadata' => 'array',
    ];



    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }



}
