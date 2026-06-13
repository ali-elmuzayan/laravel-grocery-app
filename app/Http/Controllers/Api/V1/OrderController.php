<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Cart\Services\CartService;
use App\Domain\Orders\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly CartService $cartService,
    ) {}

    public function checkout(Request $request): JsonResponse
    {
        $cart = $this->cartService->activeCartForUser($request->user()->id);
        $order = $this->orderService->checkout($cart);

        return response()->json($order->load('items'), 201);
    }

    public function index(Request $request): JsonResponse
    {
        $orders = Order::query()->where('user_id', $request->user()->id)->latest()->paginate(20);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_unless($request->user()->can('view-order', $order), 403);

        return response()->json($order->load('items', 'shipment.events', 'statusHistories'));
    }
}
