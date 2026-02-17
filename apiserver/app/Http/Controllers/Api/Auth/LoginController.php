<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\OtpManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

final class LoginController extends Controller
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

    public function login(LoginRequest $request): JsonResponse
    {
        $email = $request->input('email');

        if ($request->filled('password')) {
            $user = User::where('email', $email)->first();

            if (! $user || ! Hash::check($request->input('password'), $user->password)) {
                return $this->invalidResponse();
            }
        } elseif ($request->filled('otp')) {
            if (! $this->otpManager->verify($email, $request->input('otp'))) {
                return $this->invalidResponse();
            }

            $this->otpManager->clear($email);
            $user = User::where('email', $email)->first();

            if (! $user) {
                return $this->invalidResponse();
            }
        } else {
            return $this->invalidResponse();
        }

        $token = $user->createToken($request->input('device_name', 'nuxt'))->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    private function invalidResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }
}
