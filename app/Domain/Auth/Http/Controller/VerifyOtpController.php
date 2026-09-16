<?php

namespace App\Domain\Auth\Http\Controller;

use App\Domain\Auth\Http\Requests\VerifyOtpRequest;
use App\Domain\Auth\Service\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class VerifyOtpController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function store(VerifyOtpRequest $request): JsonResponse
    {
        $this->authService->verifyOtp(
            $request->validated('email'),
            $request->validated('otp'),
            $request->otpType(),
        );

        return response()->json([
            'message' => 'OTP verified successfully.',
        ]);
    }
}
