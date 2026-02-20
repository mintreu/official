<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\PaymentMethodCast;
use App\Casts\TransactionStatusCast;
use App\Casts\TransactionTypeCast;
use App\Models\Licensing\License;
use App\Models\Products\Plan;
use App\Services\MoneyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'wallet_id',
        'license_id',
        'plan_id',
        'transactionable_type',
        'transactionable_id',
        'type',
        'status',
        'amount',
        'amount_paise',
        'fee',
        'tax',
        'net_amount',
        'currency',
        'payment_method',
        'checkout_type',
        'integration_id',
        'provider_order_id',
        'provider_transaction_id',
        'provider_signature',
        'provider_gen_id',
        'provider_gen_session',
        'provider_gen_link',
        'provider_gen_qr',
        'provider_generated_sign',
        'qr_code_url',
        'success_url',
        'failure_url',
        'verified',
        'verified_at',
        'description',
        'purpose',
        'notes',
        'reference',
        'reference_number',
        'parent_transaction_id',
        'expires_at',
        'balance_after',
        'balance_after_paise',
        'metadata',
        'provider_response',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'amount_paise' => 'integer',
            'fee' => 'integer',
            'tax' => 'integer',
            'net_amount' => 'integer',
            'balance_after' => 'integer',
            'balance_after_paise' => 'integer',
            'type' => TransactionTypeCast::class,
            'status' => TransactionStatusCast::class,
            'payment_method' => PaymentMethodCast::class,
            'verified' => 'boolean',
            'verified_at' => 'datetime',
            'expires_at' => 'datetime',
            'metadata' => 'array',
            'provider_response' => 'array',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction): void {
            if (! $transaction->uuid) {
                $transaction->uuid = 'TXN-'.Str::upper(Str::random(12));
            }

            if (! $transaction->reference_number && ! $transaction->reference) {
                $transaction->reference_number = 'REF-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
            }

            if (! isset($transaction->amount) && isset($transaction->amount_paise)) {
                $transaction->amount = $transaction->amount_paise;
            }
            if (! isset($transaction->amount_paise) && isset($transaction->amount)) {
                $transaction->amount_paise = $transaction->amount;
            }

            if (! isset($transaction->balance_after) && isset($transaction->balance_after_paise)) {
                $transaction->balance_after = $transaction->balance_after_paise;
            }
            if (! isset($transaction->balance_after_paise) && isset($transaction->balance_after)) {
                $transaction->balance_after_paise = $transaction->balance_after;
            }

            if (! $transaction->net_amount) {
                $transaction->net_amount = (int) ($transaction->amount ?? 0) - (int) ($transaction->fee ?? 0) - (int) ($transaction->tax ?? 0);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class, 'integration_id');
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parentTransaction(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_transaction_id');
    }

    public function childTransactions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_transaction_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === TransactionStatusCast::COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === TransactionStatusCast::PENDING;
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => TransactionStatusCast::COMPLETED,
            'verified' => true,
            'verified_at' => now(),
        ]);
    }

    public function markAsFailed(?string $reason = null): void
    {
        $this->status = TransactionStatusCast::FAILED;
        if ($reason) {
            $this->notes = $reason;
        }
        $this->save();
    }

    public function getAmountInRupeesAttribute(): float
    {
        return MoneyService::toRupees($this->amount_paise ?? $this->amount ?? 0);
    }

    public function getFormattedAmountAttribute(): string
    {
        $value = (int) ($this->amount_paise ?? $this->amount ?? 0);
        $prefix = ($this->type instanceof TransactionTypeCast && $this->type->isPositive()) ? '+' : '-';

        return $prefix.MoneyService::format($value);
    }
}
