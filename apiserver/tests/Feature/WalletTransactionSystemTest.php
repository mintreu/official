<?php

use App\Casts\PaymentMethodCast;
use App\Casts\TransactionStatusCast;
use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\User;
use App\Services\UserServices\UserWalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('wallet service debits balance and writes completed transaction', function () {
    $user = User::factory()->create();
    $walletService = app(UserWalletService::class);
    $wallet = $walletService->getOrCreateWallet($user);
    $wallet->update(['balance_paise' => 25000]);

    $transaction = $walletService->debit($wallet->fresh(), 5000, 'subscription', 'Monthly charge');

    expect($transaction->status)->toBe(TransactionStatusCast::COMPLETED);
    expect((int) $transaction->amount_paise)->toBe(5000);
    expect((int) $wallet->fresh()->balance_paise)->toBe(20000);
});

test('hasTransaction trait creates payment transaction on license model', function () {
    $user = User::factory()->create();

    $product = Product::factory()->create([
        'type' => ProductType::ApiService,
        'default_license' => LicenseType::ApiSubscription,
    ]);

    $plan = Plan::query()->create([
        'product_id' => $product->id,
        'slug' => 'pro',
        'name' => 'Pro',
        'price_cents' => 9900,
        'billing_cycle' => 'monthly',
        'is_active' => true,
    ]);

    $license = License::factory()->create([
        'product_id' => $product->id,
        'plan_id' => $plan->id,
        'user_id' => $user->id,
        'type' => LicenseType::ApiSubscription,
    ]);

    $transaction = $license->createDebitTransaction(
        customer: $user,
        paymentMethod: PaymentMethodCast::CASHFREE,
        redirectSuccessUrl: 'https://example.com/success',
        redirectFailureUrl: 'https://example.com/failure',
        purpose: 'api_subscription'
    );

    expect($transaction->transactionable_type)->toBe(License::class);
    expect((int) $transaction->transactionable_id)->toBe($license->id);
    expect((int) $transaction->amount_paise)->toBe(9900);
    expect($transaction->payment_method)->toBe(PaymentMethodCast::CASHFREE);
});
