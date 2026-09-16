<?php

namespace App\Domain\Auth\Http\Controller;

use App\Domain\Auth\Http\Requests\ResendOtpRequest;
use App\Domain\Auth\Service\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ResendOtpController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function store(ResendOtpRequest $request): JsonResponse
    {
        $this->authService->resendOtp(
            $request->validated('email'),
            $request->otpType(),
        );

        return response()->json([
            'message' => 'OTP resent to your email.',
        ]);
    }
}
