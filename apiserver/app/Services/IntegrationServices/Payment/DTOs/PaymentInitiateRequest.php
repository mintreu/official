<?php

declare(strict_types=1);

namespace App\Services\IntegrationServices\Payment\DTOs;

use App\Casts\PaymentMethodCast;

final readonly class PaymentInitiateRequest
{
    public function __construct(
        public int $amountInPaisa,
        public string $currency,
        public PaymentMethodCast $method,
        public ?string $userFingerprint,
        public int $userId,
        public int $walletId,
        public string $transactionId,
        public string $customerName,
        public ?string $customerEmail,
        public ?string $customerPhone,
        public ?string $purpose,
        public ?string $description,
        public array $metadata,
        public string $callbackUrl,
        public int $expiresInMinutes = 60
    ) {}
}
