<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Licensing\License;
use App\Models\Api\ApiSpace;
use App\Models\Products\ProductReview;
use App\Models\Saas\LicensedSite;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'about',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function apiSpaces(): HasMany
    {
        return $this->hasMany(ApiSpace::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function licensedSites(): HasMany
    {
        return $this->hasMany(LicensedSite::class);
    }

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
