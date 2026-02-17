<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\OtpManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailRegisterRequest;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

final class RegisterController extends Controller
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

    public function register(EmailRegisterRequest $request): JsonResponse
    {
        $email = $request->input('email');

        if (! $this->otpManager->verify($email, $request->input('otp'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 422);
        }

        $this->otpManager->clear($email);

        /** @var User $user */
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;
        $user->notify(new WelcomeNotification());

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at?->toDateTimeString(),
                ],
                'token' => $token,
            ],
        ], 201);
    }
}
