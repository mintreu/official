<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\MoneyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Wallet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'uuid',
        'balance_paise',
        'hold_balance_paise',
        'total_credited_paise',
        'total_debited_paise',
        'currency',
        'status',
        'points',
        'pin',
        'pin_updated_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'balance_paise' => 'integer',
            'hold_balance_paise' => 'integer',
            'total_credited_paise' => 'integer',
            'total_debited_paise' => 'integer',
            'points' => 'integer',
            'pin_updated_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Wallet $wallet): void {
            if (! $wallet->uuid) {
                $wallet->uuid = 'WAL-'.Str::upper(Str::random(12));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }

    public function getAvailableBalancePaiseAttribute(): int
    {
        return max(0, (int) $this->balance_paise - (int) $this->hold_balance_paise);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return MoneyService::format($this->balance_paise);
    }

    public function canTransact(): bool
    {
        return $this->status === 'active';
    }

    public function canReceive(): bool
    {
        return $this->status === 'active';
    }

    public function hasSufficientBalance(int $amountPaise): bool
    {
        return $this->available_balance_paise >= $amountPaise;
    }
}
