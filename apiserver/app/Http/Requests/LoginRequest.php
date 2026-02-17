<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required_without:otp', 'nullable', 'string'],
            'otp' => ['required_without:password', 'nullable', 'string', 'size:6', 'regex:/^\d{6}$/'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'password.required_without' => 'Either password or OTP is required.',
            'otp.required_without' => 'Either OTP or password is required.',
            'otp.size' => 'OTP must be exactly 6 digits.',
            'otp.regex' => 'OTP must contain only numbers.',
        ];
    }
}
