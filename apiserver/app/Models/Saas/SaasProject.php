<?php

namespace App\Models\Saas;

use Illuminate\Database\Eloquent\Model;

class SaasProject extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'environment',
        'base_url',
        'internal_key',
        'internal_secret',
        'is_active',
        'last_heartbeat_at',
        'last_machine_info',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_heartbeat_at' => 'datetime',
            'last_machine_info' => 'array',
            'internal_secret' => 'encrypted',
            'meta' => 'array',
        ];
    }
}
