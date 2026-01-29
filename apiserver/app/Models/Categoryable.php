<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Categoryable extends Model
{
    protected $table = 'categoryables';

    protected $fillable = [
        'category_id',
        'categoryable_id',
        'categoryable_type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryable()
    {
        return $this->morphTo();
    }
}