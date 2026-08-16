<?php

namespace App\Domain\Auth\Http\Controller;

use App\Domain\Auth\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Domain\Auth\Service\AuthService;

class RegisteredUserController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function store(RegisterRequest $request) 
    {
        $user = $this->authService->register($request->toDTO()); 

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    }
}
