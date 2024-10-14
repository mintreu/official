<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'amount',
        'subtotal',
        'discount',
        'tax',
        'total',
        'quantity',
        'voucher',
        'tracking_id',
        'status',
        'payment_success',
        'expire_at',
        'user_id',
        'plan_id',
        'product_id',
    ];



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_success' => 'boolean',
        'expire_at' => 'datetime',
    ];


    /**
     * Generate a unique UUID for the order.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }



    /**
     * Get the user associated with the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * Get the plan associated with the order (nullable).
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class,'plan_id','id');
    }

    /**
     * Get the product associated with the order.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }


    /**
     * Calculate the total price for the order.
     */
    public function calculateTotal(): int
    {
        $subtotalAfterDiscount = $this->subtotal - $this->discount;
        $total = $subtotalAfterDiscount + $this->tax;
        return $total;
    }

    /**
     * Check if the order has expired.
     */
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expire_at);
    }

    /**
     * Mark the order as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'payment_success' => true,
            'status' => 'completed',
        ]);
    }



}
