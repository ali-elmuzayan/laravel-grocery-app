<?php

namespace App\Domain\Auth\Http\Controller;

use App\Domain\Auth\Http\Requests\ForgotPasswordRequest;
use App\Domain\Auth\Service\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ForgetPasswordController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function store(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgetPassword($request->validated('email'));

        return response()->json([
            'message' => 'OTP sent to your email.',
        ]);
    }
}
