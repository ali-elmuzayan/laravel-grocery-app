<?php

namespace App\Domain\Auth\Http\Controller;

use App\Domain\Auth\Http\Requests\ResetPasswordRequest;
use App\Domain\Auth\Service\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function store(ResetPasswordRequest $request): JsonResponse
    {
        $this->authService->resetPassword(
            $request->validated('email'),
            $request->validated('password'),
        );

        return response()->json([
            'message' => 'Password reset successfully.',
        ]);
    }
}
