<?php

namespace App\Models\Product;

use App\Models\Category\Category;
use App\Models\Enums\Product\ProductTypeCast;
use App\Models\Project\Project;
use App\Models\Project\ProjectPlan;
use App\Models\Service\Service;
use App\Models\Subscription\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model  implements HasMedia
{
    use HasFactory,InteractsWithMedia;





    protected $fillable = [
        'type',
        'name',
        'url',
        'status',
        'popularity',
        'views',
        'featured',
        'visibility',
        'service_id',
        'category_id',
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
        'popularity' => 'integer',
        'views' => 'integer',
        'featured' => 'boolean',
        'visibility' => 'boolean',
    ];




    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('displayImage')
            ->useFallbackUrl('https://placehold.co/800x600/violet/white?text=Product+Image');
    }


    public function flat(): HasOne
    {
        return $this->hasOne(ProductFlat::class,'product_id','id');
    }

    public function plans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductPlan::class,'product_id','id');
    }


    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function services(): BelongsTo
    {
        return $this->belongsTo(Service::class,'service_id','id');
    }




}
