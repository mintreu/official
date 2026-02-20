<?php

namespace App\Services\Saas;

use App\Casts\PaymentMethodCast;
use App\Casts\TransactionStatusCast;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\UserServices\UserWalletService;

final class SubscriptionBillingService
{
    public function __construct(private readonly UserWalletService $walletService) {}

    public function ensureWallet(User $user): Wallet
    {
        return $this->walletService->getOrCreateWallet($user);
    }

    public function chargeSubscription(User $user, License $license, Plan $plan, Product $product): ?array
    {
        $amountPaise = max(0, (int) $plan->price_cents);
        if ($amountPaise <= 0) {
            return null;
        }

        $wallet = $this->ensureWallet($user);
        $available = (int) $wallet->available_balance_paise;

        if ($available >= $amountPaise) {
            $transaction = $this->walletService->debit(
                wallet: $wallet,
                amountInPaisa: $amountPaise,
                purpose: 'subscription',
                description: "Subscription charge for {$product->slug}",
                relatedModel: $license
            );

            $transaction->update([
                'license_id' => $license->id,
                'plan_id' => $plan->id,
                'payment_method' => PaymentMethodCast::WALLET,
                'meta' => [
                    'product_slug' => $product->slug,
                    'plan_slug' => $plan->slug,
                    'license_key' => $license->license_key,
                ],
                'reference' => 'sub_'.$license->id.'_'.now()->format('YmdHis'),
            ]);
        } else {
            $transaction = Transaction::query()->create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'license_id' => $license->id,
                'plan_id' => $plan->id,
                'type' => 'debit',
                'status' => TransactionStatusCast::PENDING,
                'amount' => $amountPaise,
                'amount_paise' => $amountPaise,
                'balance_after' => $available,
                'balance_after_paise' => $available,
                'currency' => 'INR',
                'payment_method' => PaymentMethodCast::CASHFREE,
                'purpose' => 'subscription',
                'description' => "Pending subscription charge for {$product->slug}",
                'reference' => 'sub_'.$license->id.'_'.now()->format('YmdHis'),
                'meta' => [
                    'product_slug' => $product->slug,
                    'plan_slug' => $plan->slug,
                    'license_key' => $license->license_key,
                ],
                'metadata' => [
                    'product_slug' => $product->slug,
                    'plan_slug' => $plan->slug,
                    'license_key' => $license->license_key,
                ],
            ]);
        }

        return [
            'transaction_id' => $transaction->id,
            'status' => $transaction->status instanceof TransactionStatusCast ? $transaction->status->value : (string) $transaction->status,
            'amount_paise' => (int) ($transaction->amount_paise ?? $transaction->amount ?? 0),
            'balance_after_paise' => (int) ($transaction->balance_after_paise ?? $transaction->balance_after ?? 0),
        ];
    }
}
