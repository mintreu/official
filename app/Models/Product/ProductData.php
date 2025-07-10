<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductData extends Model
{
    use HasFactory;

    protected $table = 'product_data';

    protected $fillable = [
        'desc',
        'product_id',
        'host_url',
        'host_api_url',
        'client_login_url',
        'demo_accounts',
        'product_info',
        'metadata',
    ];

    protected $casts = [
        'demo_accounts' => 'array',
        'product_info'   => 'array',
        'metadata'       => 'array',
    ];

    /**
     * Get the product this data belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
