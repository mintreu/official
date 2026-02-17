<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Contracts\Helpers\OtpManagerInterface;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use RuntimeException;

final class OtpManager implements OtpManagerInterface
{
    public const CREDENTIAL_EMAIL = 'email';

    private const OTP_TTL_MINUTES = 15;
    private const OTP_MIN = 100000;
    private const OTP_MAX = 999999;
    private const DEMO_OTP = 123456;
    private const MAX_ATTEMPTS = 5;

    public function __construct(
        private readonly CacheContract $cache,
        private readonly Hasher $hasher,
        private readonly bool $isDemoMode = false
    ) {}

    public function generate(string $credential): int
    {
        $this->validateCredential($credential);
        $this->enforceRateLimit($credential);

        if ($this->isDemoMode) {
            return $this->storeOtp($credential, self::DEMO_OTP);
        }

        $otp = random_int(self::OTP_MIN, self::OTP_MAX);

        return $this->storeOtp($credential, $otp);
    }

    public function sendOtp(string $credential, string $purpose = 'verification'): array
    {
        try {
            $otp = $this->generate($credential);
            $sent = $this->sendEmail($credential, (string) $otp, $purpose);

            return [
                'success' => $sent,
                'message' => $sent ? 'OTP sent successfully' : 'Failed to deliver OTP',
                'demo' => $this->isDemoMode,
                'otp' => $this->isDemoMode ? $otp : null,
            ];
        } catch (RuntimeException $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode() ?: 422,
            ];
        }
    }

    public function verify(string $credential, string $otp): bool
    {
        $this->validateCredential($credential);
        $this->incrementAttempts($credential);

        $stored = $this->retrieve($credential);

        if (! $stored || ! $this->hasher->check($otp, $stored)) {
            $this->handleFailedAttempt($credential);
            return false;
        }

        $this->clearAttempts($credential);
        return true;
    }

    public function clear(string $credential): void
    {
        $this->cache->forget($this->getCacheKey($credential));
        $this->clearAttempts($credential);
    }

    public function exists(string $credential): bool
    {
        return $this->retrieve($credential) !== null;
    }

    public function getRemainingAttempts(string $credential): int
    {
        $key = $this->getAttemptsKey($credential);
        $attempts = (int) $this->cache->get($key, 0);

        return max(0, self::MAX_ATTEMPTS - $attempts);
    }

    public function getCooldownSeconds(string $credential): int
    {
        $key = $this->getRateLimitKey($credential);
        $count = (int) $this->cache->get($key, 0);

        if ($count >= 3) {
            return self::OTP_TTL_MINUTES * 60;
        }

        return 0;
    }

    public function isDemoMode(): bool
    {
        return $this->isDemoMode;
    }

    private function storeOtp(string $credential, int $otp): int
    {
        $key = $this->getCacheKey($credential);
        $hashed = $this->hasher->make((string) $otp);
        $this->cache->put($key, $hashed, now()->addMinutes(self::OTP_TTL_MINUTES));

        return $otp;
    }

    private function retrieve(string $credential): ?string
    {
        return $this->cache->get($this->getCacheKey($credential));
    }

    private function sendEmail(string $email, string $otp, string $purpose): bool
    {
        try {
            Notification::route('mail', $email)->notify(new \App\Notifications\OtpNotification($otp, $purpose));

            return true;
        } catch (\Exception $exception) {
            Log::error('OTP notification delivery failed', [
                'email' => $this->maskCredential($email),
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function enforceRateLimit(string $credential): void
    {
        $key = $this->getRateLimitKey($credential);
        $count = (int) $this->cache->get($key, 0);

        if ($count >= 3) {
            throw new RuntimeException('Too many OTP requests. Please try again later.', 429);
        }

        $this->cache->put($key, $count + 1, now()->addMinutes(self::OTP_TTL_MINUTES));
    }

    private function incrementAttempts(string $credential): void
    {
        $key = $this->getAttemptsKey($credential);
        $this->cache->put($key, (int) $this->cache->get($key, 0) + 1, now()->addMinutes(30));
    }

    private function handleFailedAttempt(string $credential): void
    {
        $key = $this->getAttemptsKey($credential);
        $attempts = (int) $this->cache->get($key, 0);

        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->cache->forget($this->getCacheKey($credential));
        }
    }

    private function clearAttempts(string $credential): void
    {
        $this->cache->forget($this->getAttemptsKey($credential));
    }

    private function getCacheKey(string $credential): string
    {
        return 'otp:'.hash('xxh3', $credential);
    }

    private function getRateLimitKey(string $credential): string
    {
        return 'otp_rate_limit:'.$credential;
    }

    private function getAttemptsKey(string $credential): string
    {
        return 'otp_attempts:'.$credential;
    }

    private function validateCredential(string $credential): void
    {
        if (trim($credential) === '') {
            throw new RuntimeException('Credential cannot be empty.', 422);
        }
    }

    private function maskCredential(string $credential): string
    {
        $parts = explode('@', $credential);

        if (count($parts) === 2) {
            return substr($parts[0], 0, 2).'***@'.$parts[1];
        }

        return substr($credential, 0, 3).'***';
    }
}
