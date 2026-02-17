<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\OtpManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class OtpController extends Controller
{
    private readonly OtpManager $otpManager;

    public function __construct()
    {
        $this->otpManager = new OtpManager(
            cache()->store(),
            app('hash'),
            (bool) config('app.debug', false)
        );
    }

    public function send(SendOtpRequest $request): JsonResponse
    {
        $result = $this->otpManager->sendOtp(
            $request->input('email'),
            $request->input('purpose', 'verification')
        );

        return response()->json(
            [
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? 'OTP send failed',
                'demo' => $result['demo'] ?? false,
                'otp' => $result['otp'] ?? null,
            ],
            $result['success'] ? 200 : (int) ($result['code'] ?? 422)
        );
    }

    public function verify(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $valid = $this->otpManager->verify($request->input('email'), $request->input('otp'));

            if ($valid) {
                $this->otpManager->clear($request->input('email'));

                return response()->json([
                    'success' => true,
                    'valid' => true,
                    'message' => 'OTP verified successfully',
                ]);
            }

            return $this->invalidResponse('OTP expired or invalid.');
        } catch (\RuntimeException $exception) {
            Log::warning('OTP verification failed', ['error' => $exception->getMessage()]);

            return $this->invalidResponse($exception->getMessage(), $exception->getCode() === 429 ? 429 : 422);
        }
    }

    private function invalidResponse(string $message, int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'valid' => false,
            'message' => $message,
        ], $status);
    }
}
