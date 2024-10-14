<?php

namespace App\Models\Product;

use App\Models\Category\Category;
use App\Models\Enums\ProductTypeCast;
use App\Models\Project\Project;
use App\Models\Project\ProjectPlan;
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
        'desc',
        'status',
        'chargeable',
        'base_price',
        'tax_percent',
        'tax_amount',
        'price',
        'popularity',
        'views',
        'featured',
        'visibility',
        'api_url',
        'demo_accounts',
        'metadata',
        'project_id',
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
        'base_price' => 'float',
        'tax_percent' => 'float',
        'tax_amount' => 'float',
        'price' => 'float',
        'popularity' => 'integer',
        'views' => 'integer',
        'featured' => 'boolean',
        'visibility' => 'boolean',
        'demo_accounts' => 'array', // JSON cast as array
        'metadata' => 'array',      // JSON cast as array
        'project_id' => 'integer',
    ];




    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('displayImage')
            ->useFallbackUrl('https://placehold.co/800x600/violet/white?text=Product+Image');
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




    // Accessor to get the full price (price + tax_amount)
    public function getFullPriceAttribute()
    {
        return $this->price + $this->tax_amount;
    }

    // Example method to calculate discount
    public function applyDiscount(float $discount)
    {
        $discountedPrice = $this->price * ((100 - $discount) / 100);
        return round($discountedPrice, 2);
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
