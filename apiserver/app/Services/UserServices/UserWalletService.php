<?php

declare(strict_types=1);

namespace App\Services\UserServices;

use App\Casts\TransactionStatusCast;
use App\Casts\TransactionTypeCast;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class UserWalletService
{
    public function getOrCreateWallet(User $owner, string $currency = 'INR'): Wallet
    {
        return Wallet::query()->firstOrCreate(
            ['user_id' => $owner->id],
            [
                'currency' => $currency,
                'balance_paise' => 0,
                'hold_balance_paise' => 0,
                'total_credited_paise' => 0,
                'total_debited_paise' => 0,
                'status' => 'active',
            ]
        );
    }

    public function credit(
        Wallet $wallet,
        int $amountInPaisa,
        string $purpose,
        ?string $description = null,
        ?Model $relatedModel = null
    ): Transaction {
        if (! $wallet->canReceive()) {
            throw new RuntimeException('Wallet cannot receive funds');
        }

        return DB::transaction(function () use ($wallet, $amountInPaisa, $purpose, $description, $relatedModel): Transaction {
            $wallet->increment('balance_paise', $amountInPaisa);
            $wallet->increment('total_credited_paise', $amountInPaisa);
            $wallet->refresh();

            return Transaction::query()->create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'transactionable_type' => $relatedModel ? $relatedModel::class : null,
                'transactionable_id' => $relatedModel?->getKey(),
                'type' => TransactionTypeCast::CREDIT,
                'status' => TransactionStatusCast::COMPLETED,
                'amount' => $amountInPaisa,
                'amount_paise' => $amountInPaisa,
                'net_amount' => $amountInPaisa,
                'currency' => $wallet->currency ?? 'INR',
                'purpose' => $purpose,
                'description' => $description,
                'verified' => true,
                'verified_at' => now(),
                'balance_after' => $wallet->balance_paise,
                'balance_after_paise' => $wallet->balance_paise,
            ]);
        });
    }

    public function debit(
        Wallet $wallet,
        int $amountInPaisa,
        string $purpose,
        ?string $description = null,
        ?Model $relatedModel = null
    ): Transaction {
        if (! $wallet->canTransact()) {
            throw new RuntimeException('Wallet cannot transact');
        }

        if (! $wallet->hasSufficientBalance($amountInPaisa)) {
            throw new RuntimeException('Insufficient balance');
        }

        return DB::transaction(function () use ($wallet, $amountInPaisa, $purpose, $description, $relatedModel): Transaction {
            $wallet->decrement('balance_paise', $amountInPaisa);
            $wallet->increment('total_debited_paise', $amountInPaisa);
            $wallet->refresh();

            return Transaction::query()->create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'transactionable_type' => $relatedModel ? $relatedModel::class : null,
                'transactionable_id' => $relatedModel?->getKey(),
                'type' => TransactionTypeCast::DEBIT,
                'status' => TransactionStatusCast::COMPLETED,
                'amount' => $amountInPaisa,
                'amount_paise' => $amountInPaisa,
                'net_amount' => $amountInPaisa,
                'currency' => $wallet->currency ?? 'INR',
                'purpose' => $purpose,
                'description' => $description,
                'verified' => true,
                'verified_at' => now(),
                'balance_after' => $wallet->balance_paise,
                'balance_after_paise' => $wallet->balance_paise,
            ]);
        });
    }

    public function hold(Wallet $wallet, int $amountInPaisa, string $purpose, ?string $description = null): Transaction
    {
        if (! $wallet->canTransact()) {
            throw new RuntimeException('Wallet cannot transact');
        }

        if (! $wallet->hasSufficientBalance($amountInPaisa)) {
            throw new RuntimeException('Insufficient balance');
        }

        return DB::transaction(function () use ($wallet, $amountInPaisa, $purpose, $description): Transaction {
            $wallet->decrement('balance_paise', $amountInPaisa);
            $wallet->increment('hold_balance_paise', $amountInPaisa);
            $wallet->refresh();

            return Transaction::query()->create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'type' => TransactionTypeCast::HOLD,
                'status' => TransactionStatusCast::COMPLETED,
                'amount' => $amountInPaisa,
                'amount_paise' => $amountInPaisa,
                'net_amount' => $amountInPaisa,
                'currency' => $wallet->currency ?? 'INR',
                'purpose' => $purpose,
                'description' => $description ?? 'Funds held',
                'verified' => true,
                'verified_at' => now(),
                'balance_after' => $wallet->balance_paise,
                'balance_after_paise' => $wallet->balance_paise,
            ]);
        });
    }

    public function release(Wallet $wallet, int $amountInPaisa, string $purpose): Transaction
    {
        if ((int) $wallet->hold_balance_paise < $amountInPaisa) {
            throw new RuntimeException('Insufficient held funds');
        }

        return DB::transaction(function () use ($wallet, $amountInPaisa, $purpose): Transaction {
            $wallet->decrement('hold_balance_paise', $amountInPaisa);
            $wallet->increment('balance_paise', $amountInPaisa);
            $wallet->refresh();

            return Transaction::query()->create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'type' => TransactionTypeCast::RELEASE,
                'status' => TransactionStatusCast::COMPLETED,
                'amount' => $amountInPaisa,
                'amount_paise' => $amountInPaisa,
                'net_amount' => $amountInPaisa,
                'currency' => $wallet->currency ?? 'INR',
                'purpose' => $purpose,
                'description' => 'Funds released',
                'verified' => true,
                'verified_at' => now(),
                'balance_after' => $wallet->balance_paise,
                'balance_after_paise' => $wallet->balance_paise,
            ]);
        });
    }
}
