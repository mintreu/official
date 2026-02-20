<?php

namespace App\Models;

use App\Enums\IntegrationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'type',
        'credentials',
        'settings',
        'is_sandbox',
        'is_active',
        'is_default',
        'last_tested_at',
        'last_test_result',
        'last_test_message',
    ];

    protected function casts(): array
    {
        return [
            'type' => IntegrationType::class,
            'credentials' => 'encrypted:array',
            'settings' => 'array',
            'is_sandbox' => 'boolean',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'last_tested_at' => 'datetime',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'integration_id');
    }
}
