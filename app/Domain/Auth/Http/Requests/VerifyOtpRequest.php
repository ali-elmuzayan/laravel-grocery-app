<?php

namespace App\Domain\Auth\Http\Requests;

use App\Domain\Auth\Enums\OtpType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'digits:6'],
        ];
    }

    public function otpType(): OtpType
    {
        return $this->enum('type', OtpType::class) ?? OtpType::ForgotPassword;
    }
}
