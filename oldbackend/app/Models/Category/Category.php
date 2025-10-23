<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'parent_id',
        'short_desc',
        'desc',
        'status'
    ];


    protected $casts = [
        'status' => 'boolean'
    ];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class,'category_id','id');
    }






}
