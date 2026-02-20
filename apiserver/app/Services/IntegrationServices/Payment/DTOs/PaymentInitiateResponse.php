<?php

declare(strict_types=1);

namespace App\Services\IntegrationServices\Payment\DTOs;

use App\Casts\TransactionStatusCast;

final readonly class PaymentInitiateResponse
{
    public function __construct(
        public bool $success,
        public string $status,
        public ?string $providerOrderId,
        public ?string $providerTransactionId,
        public ?string $message,
        public array $metadata = []
    ) {}

    public function getStatusEnum(): TransactionStatusCast
    {
        return match ($this->status) {
            'completed', 'success' => TransactionStatusCast::COMPLETED,
            'processing' => TransactionStatusCast::PROCESSING,
            'failed' => TransactionStatusCast::FAILED,
            default => TransactionStatusCast::PENDING,
        };
    }
}
