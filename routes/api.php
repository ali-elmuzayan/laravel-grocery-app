<?php

use App\Domain\Catalog\Http\Controllers\CategoryController;
use App\Domain\Catalog\Http\Controllers\ProductController;
use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\DeliveryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;


// Categories routes 
Route::apiResource('/categories', CategoryController::class)->only(['index', 'show']);

// Products Routes; 
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);





Route::prefix('v1')->group(function (): void {
    Route::middleware(['auth:api', 'throttle:api'])->group(function (): void {
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



Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
    ]);
});


require base_path('app/Domain/Auth/routes/auth.php');
