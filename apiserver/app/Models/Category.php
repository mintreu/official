<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphToMany};
use Illuminate\Support\Str;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function categoryables(): HasMany
    {
        return $this->hasMany(Categoryable::class);
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(\App\Models\Content\Article::class, 'categoryable');
    }

    public function caseStudies(): MorphToMany
    {
        return $this->morphedByMany(\App\Models\Content\CaseStudy::class, 'categoryable');
    }

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(\App\Models\Content\Project::class, 'categoryable');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(\App\Models\Product::class, 'categoryable');
    }

    public function scopeTree($query)
    {
        return $query->whereNull('parent_id')->active()->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getChildrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public static function getTreeRecursive(): \Illuminate\Database\Eloquent\Collection
    {
        return self::tree()->with('childrenRecursive')->get();
    }
}
