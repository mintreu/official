<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'content',
        'image',
        'price',
        'category',
        'type',
        'download_url',
        'demo_url',
        'github_url',
        'documentation_url',
        'version',
        'downloads',
        'rating',
        'status',
        'featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'downloads' => 'integer',
        'rating' => 'float',
        'featured' => 'boolean',
        'status' => \App\Casts\PublishableStatusCast::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }
}
