<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\DeliveryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::middleware('throttle:login')->group(function (): void {
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    });

    Route::middleware(['auth:api', 'throttle:api'])->group(function (): void {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::get('/catalog/products', [CatalogController::class, 'index']);
        Route::get('/catalog/categories', [CatalogController::class, 'categories']);

        Route::middleware('verified.user')->group(function (): void {
            Route::get('/cart', [CartController::class, 'show']);
            Route::post('/cart/items', [CartController::class, 'store']);
            Route::delete('/cart/items/{item}', [CartController::class, 'destroy']);

            Route::post('/orders/checkout', [OrderController::class, 'checkout']);
            Route::get('/orders', [OrderController::class, 'index']);
            Route::get('/orders/{order}', [OrderController::class, 'show']);

            Route::post('/payments/intents', [PaymentController::class, 'createIntent']);
            Route::post('/payments/intents/{paymentIntent}/capture', [PaymentController::class, 'captureIntent']);
            Route::post('/payments/payouts/request', [PaymentController::class, 'requestPayout']);

            Route::get('/deliveries/orders/{order}/timeline', [DeliveryController::class, 'timeline']);
        });
    });

    Route::middleware(['auth:api', 'can:admin-access'])->prefix('admin')->group(function (): void {
        Route::post('/categories', [AdminController::class, 'storeCategory']);
        Route::patch('/products/{product}/approve', [AdminController::class, 'approveProduct']);
        Route::patch('/products/{product}/reject', [AdminController::class, 'rejectProduct']);
    });
});
