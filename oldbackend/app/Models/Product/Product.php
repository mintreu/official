<?php

namespace App\Models\Product;

use App\Models\Category\Category;
use App\Models\Enums\ProductTypeCast;
use App\Models\Project\Project;
use App\Models\Subscription\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model  implements HasMedia
{
    use HasFactory,InteractsWithMedia;






    protected $fillable = [
        'type',
        'name',
        'url',
        'short_desc',

        'status',
        'chargeable',
        'popularity',
        'views',
        'featured',
        'visibility',
        'api_url',
        'metadata',
        'project_id',
        'parent_id'
    ];



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'type' => ProductTypeCast::class,
        'status' => 'boolean',
        'chargeable' => 'boolean',

        'popularity' => 'integer',
        'views' => 'integer',
        'featured' => 'boolean',
        'visibility' => 'boolean',
        'demo_accounts' => 'array', // JSON cast as array
        'metadata' => 'array',      // JSON cast as array
        'project_id' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'url';
    }



    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('displayImage')
            ->useFallbackUrl('https://placehold.co/800x600/violet/white?text=Product+Image');

        $this->addMediaCollection('imageGallery')
            ->useFallbackUrl('https://placehold.co/1200x800/brown/white?text=Screenshot');
    }


    public function data()
    {
        return $this->hasOne(ProductData::class,'product_id','id');
    }

    public function children()
    {
        return $this->hasMany(Product::class,'parent_id','id');
    }


    public function parent()
    {
        return $this->belongsToMany(Product::class,'parent_id','id');
    }


    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }


    public function plans()
    {
        return $this->hasMany(Plan::class,'product_id','id');
    }





    // Scope to filter by visibility
    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }

    // Scope to filter featured products
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }



}
