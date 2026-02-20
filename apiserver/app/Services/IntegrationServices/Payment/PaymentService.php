<?php

declare(strict_types=1);

namespace App\Services\IntegrationServices\Payment;

use App\Casts\PaymentMethodCast;
use App\Models\Integration;
use App\Services\IntegrationServices\Payment\DTOs\PaymentInitiateRequest;
use App\Services\IntegrationServices\Payment\DTOs\PaymentInitiateResponse;
use Illuminate\Support\Str;

final class PaymentService
{
    public function initiate(PaymentInitiateRequest $request): PaymentInitiateResponse
    {
        $integration = $this->resolveIntegration($request->method);

        // Native methods can be treated as immediately successful for internal/manual flow.
        if ($request->method->isNative()) {
            return new PaymentInitiateResponse(
                success: true,
                status: 'completed',
                providerOrderId: 'native_order_'.Str::lower(Str::random(16)),
                providerTransactionId: 'native_txn_'.Str::lower(Str::random(16)),
                message: 'Native payment accepted.',
                metadata: [
                    'integration_id' => $integration?->id,
                    'provider' => $request->method->value,
                ]
            );
        }

        return new PaymentInitiateResponse(
            success: true,
            status: 'pending',
            providerOrderId: $request->method->value.'_order_'.Str::lower(Str::random(16)),
            providerTransactionId: $request->method->value.'_txn_'.Str::lower(Str::random(16)),
            message: strtoupper($request->method->value).' payment initiated.',
            metadata: [
                'integration_id' => $integration?->id,
                'provider' => $request->method->value,
                'provider_gen_id' => 'gen_'.Str::lower(Str::random(12)),
                'provider_gen_session' => 'sess_'.Str::lower(Str::random(20)),
                'provider_gen_link' => $request->callbackUrl,
            ]
        );
    }

    private function resolveIntegration(PaymentMethodCast $method): ?Integration
    {
        return Integration::query()
            ->where('type', 'payment')
            ->where('slug', $method->value)
            ->where('is_active', true)
            ->first()
            ?? Integration::query()
                ->where('type', 'payment')
                ->where('slug', 'native')
                ->where('is_active', true)
                ->first();
    }
}
