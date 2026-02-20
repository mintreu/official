<?php

declare(strict_types=1);

namespace App\Traits;

use App\Casts\PaymentMethodCast;
use App\Casts\TransactionStatusCast;
use App\Casts\TransactionTypeCast;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\IntegrationServices\Payment\DTOs\PaymentInitiateRequest;
use App\Services\IntegrationServices\Payment\PaymentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait HasTransaction
{
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function createDebitTransaction(
        Model|array $customer,
        PaymentMethodCast $paymentMethod,
        string $redirectSuccessUrl,
        string $redirectFailureUrl,
        ?Wallet $wallet = null,
        ?string $purpose = null,
        int $expireAfterMinutes = 60
    ): Transaction {
        return $this->createTransaction(
            customer: $customer,
            type: TransactionTypeCast::DEBIT,
            paymentMethod: $paymentMethod,
            redirectSuccessUrl: $redirectSuccessUrl,
            redirectFailureUrl: $redirectFailureUrl,
            wallet: $wallet,
            purpose: $purpose,
            expireAfterMinutes: $expireAfterMinutes
        );
    }

    public function createCreditTransaction(
        Model|array $customer,
        int $amount,
        PaymentMethodCast $paymentMethod,
        string $redirectSuccessUrl,
        string $redirectFailureUrl,
        ?Wallet $wallet = null,
        ?string $purpose = null,
        int $expireAfterMinutes = 60
    ): Transaction {
        return $this->createTransaction(
            customer: $customer,
            type: TransactionTypeCast::CREDIT,
            paymentMethod: $paymentMethod,
            redirectSuccessUrl: $redirectSuccessUrl,
            redirectFailureUrl: $redirectFailureUrl,
            wallet: $wallet,
            purpose: $purpose,
            expireAfterMinutes: $expireAfterMinutes,
            amount: $amount
        );
    }

    protected function createTransaction(
        Model|array $customer,
        TransactionTypeCast $type,
        PaymentMethodCast $paymentMethod,
        string $redirectSuccessUrl,
        string $redirectFailureUrl,
        ?Wallet $wallet = null,
        ?string $purpose = null,
        int $expireAfterMinutes = 60,
        ?int $amount = null
    ): Transaction {
        return DB::transaction(function () use (
            $customer,
            $type,
            $paymentMethod,
            $redirectSuccessUrl,
            $redirectFailureUrl,
            $wallet,
            $purpose,
            $expireAfterMinutes,
            $amount
        ) {
            $resolvedAmount = $amount ?? $this->resolveTransactionAmount();
            $customerData = $this->parseCustomerData($customer);

            $transaction = $this->transactions()->create([
                'uuid' => 'TXN-'.Str::upper(Str::random(12)),
                'user_id' => (int) ($customerData['user_id'] ?? 0) ?: null,
                'wallet_id' => $wallet?->id,
                'type' => $type,
                'status' => TransactionStatusCast::PENDING,
                'amount' => $resolvedAmount,
                'amount_paise' => $resolvedAmount,
                'currency' => 'INR',
                'payment_method' => $paymentMethod,
                'purpose' => $purpose ?? 'Payment',
                'description' => $customerData['name'].' - '.($purpose ?? 'Payment'),
                'expires_at' => now()->addMinutes($expireAfterMinutes),
                'verified' => false,
                'success_url' => $redirectSuccessUrl,
                'failure_url' => $redirectFailureUrl,
                'metadata' => ['customer' => $customerData],
                'meta' => ['customer' => $customerData],
            ]);

            $paymentService = app(PaymentService::class);
            $callbackUrl = Route::has('transaction.validate')
                ? route('transaction.validate', ['transaction' => $transaction->uuid])
                : url('/api/transactions/validate/'.$transaction->uuid);

            $paymentRequest = new PaymentInitiateRequest(
                amountInPaisa: $resolvedAmount,
                currency: 'INR',
                method: $paymentMethod,
                userFingerprint: $customerData['user_fingerprint'],
                userId: (int) ($customerData['user_id'] ?? 0),
                walletId: $wallet?->id ?? 0,
                transactionId: $transaction->uuid,
                customerName: $customerData['name'],
                customerEmail: $customerData['email'],
                customerPhone: $customerData['mobile'],
                purpose: $purpose,
                description: $transaction->description,
                metadata: $transaction->metadata ?? [],
                callbackUrl: $callbackUrl,
                expiresInMinutes: $expireAfterMinutes
            );

            $paymentResponse = $paymentService->initiate($paymentRequest);

            if ($paymentResponse->success || $paymentResponse->status === 'pending') {
                $transaction->update([
                    'provider_order_id' => $paymentResponse->providerOrderId,
                    'provider_transaction_id' => $paymentResponse->providerTransactionId,
                    'provider_gen_id' => $paymentResponse->metadata['provider_gen_id'] ?? null,
                    'provider_gen_session' => $paymentResponse->metadata['provider_gen_session'] ?? null,
                    'provider_gen_link' => $paymentResponse->metadata['provider_gen_link'] ?? null,
                    'checkout_type' => 'web',
                    'status' => $paymentResponse->getStatusEnum(),
                    'verified' => $paymentResponse->status === 'completed',
                    'verified_at' => $paymentResponse->status === 'completed' ? now() : null,
                    'provider_response' => $paymentResponse->metadata,
                    'integration_id' => $paymentResponse->metadata['integration_id'] ?? null,
                ]);
            } else {
                $transaction->update([
                    'status' => TransactionStatusCast::FAILED,
                    'provider_response' => $paymentResponse->metadata,
                ]);

                throw new \RuntimeException('Failed to create payment: '.($paymentResponse->message ?? 'Unknown error'));
            }

            return $transaction->fresh();
        });
    }

    protected function resolveTransactionAmount(): int
    {
        if (defined('static::TRANSACTION_AMOUNT_COLUMN')) {
            $column = static::TRANSACTION_AMOUNT_COLUMN;
            if (isset($this->{$column})) {
                return (int) $this->{$column};
            }
        }

        foreach (['total', 'amount', 'fee', 'price', 'price_cents'] as $column) {
            if (isset($this->{$column})) {
                return (int) $this->{$column};
            }
        }

        throw new \RuntimeException('Unable to resolve transaction amount. Define TRANSACTION_AMOUNT_COLUMN constant.');
    }

    public function hasPaymentTransaction(): bool
    {
        return $this->transactions()
            ->whereIn('status', [TransactionStatusCast::PENDING, TransactionStatusCast::COMPLETED])
            ->exists();
    }

    public function getActivePaymentTransaction(): ?Transaction
    {
        return $this->transactions()
            ->whereIn('status', [TransactionStatusCast::PENDING, TransactionStatusCast::COMPLETED])
            ->first();
    }

    protected function parseCustomerData(Model|array $customer): array
    {
        if (is_array($customer)) {
            return [
                'user_id' => $customer['id'] ?? null,
                'user_fingerprint' => $customer['fingerprint'] ?? null,
                'name' => $customer['name'] ?? 'Guest',
                'email' => $customer['email'] ?? null,
                'mobile' => $customer['mobile'] ?? null,
            ];
        }

        return [
            'user_id' => $customer->id ?? null,
            'user_fingerprint' => method_exists($customer, 'fingerprint') ? $customer->fingerprint() : null,
            'name' => $customer->name ?? 'User',
            'email' => $customer->email ?? null,
            'mobile' => $customer->mobile ?? null,
        ];
    }
}
