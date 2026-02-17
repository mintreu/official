<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

final class PasswordResetController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found',
            ], 404);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        Mail::to($email)->send(new OtpMail($token, 'password_reset'));

        return response()->json([
            'success' => true,
            'message' => 'Password reset instructions sent to your email',
            'demo' => config('app.debug', false),
            'token' => config('app.debug', false) ? $token : null,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $token = $request->input('token');

        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $resetRecord || ! Hash::check($token, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token',
            ], 422);
        }

        if ($resetRecord->created_at < now()->subMinutes(60)) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token',
            ], 422);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user->tokens()->delete();
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
        ]);
    }
}
