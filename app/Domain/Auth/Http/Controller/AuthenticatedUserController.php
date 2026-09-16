<?php

namespace App\Domain\Auth\Http\Controller;

use App\Http\Controllers\Controller;
use App\Domain\Auth\Http\Requests\LoginRequest;
use App\Domain\Auth\Service\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * Login a user 
     */
    public function store(LoginRequest $request)
    {
        $user = $this->authService->login($request->toDTO());
    }

    public function show() 
    {
        $user = Auth::user();   
        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user
        ]);
    }
}
