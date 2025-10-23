<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\UserProduct;
use App\Models\Studio\Studio;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable  implements HasMedia,FilamentUser
{
    use HasFactory, Notifiable,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatarImage')
            ->useFallbackUrl(asset('images/placeholder/user_placeholder.png'));
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canBeImpersonated()
    {
        // Let's prevent impersonating other users at our own company
        return true;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'user_id','id');
    }


    /**
     * Get the user's products via the user_products table.
     *
     * @return HasMany
     */
    public function userProducts(): HasMany
    {
        return $this->hasMany(UserProduct::class);
    }

    /**
     * Get all products that the user owns.
     *
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,        // Final model (Product)
            UserProduct::class,    // Intermediate model (UserProduct)
            'user_id',             // Foreign key on UserProduct table...
            'id',                  // Foreign key on Product table...
            'id',                  // Local key on User table...
            'product_id'           // Local key on UserProduct table...
        );
    }



    public function hostedStudios()
    {
        return $this->morphMany(Studio::class, 'host');
    }





}
