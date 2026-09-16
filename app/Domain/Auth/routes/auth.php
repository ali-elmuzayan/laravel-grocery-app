<?php

use Illuminate\Support\Facades\Route;
use App\Domain\Auth\Http\Controller\AuthenticatedUserController;
use App\Domain\Auth\Http\Controller\RegisteredUserController;


Route::prefix('auth')->middleware('guest')->group(function () {
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('login', [AuthenticatedUserController::class, 'store']);
    Route::post('/forgot-password', [AuthenticatedUserController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthenticatedUserController::class, 'forgotPassword']);
    Route::post('/verify-otp', [AuthenticatedUserController::class, 'forgotPassword']);
    Route::post('/resend-otp', [AuthenticatedUserController::class, 'forgotPassword']);
}); 
    
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthenticatedUserController::class, 'show']);
    Route::post('logout', [AuthenticatedUserController::class, 'destroy']);
});