<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Cart\Services\CartService;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function show(Request $request): JsonResponse
    {
        $cart = $this->cartService->activeCartForUser($request->user()->id)->load('items.product');

        return response()->json($cart);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cart = $this->cartService->activeCartForUser($request->user()->id);
        $product = Product::query()->findOrFail($data['product_id']);

        $updated = $this->cartService->addItem($cart, $product, $data['quantity']);

        return response()->json($updated);
    }

    public function destroy(Request $request, CartItem $item): JsonResponse
    {
        abort_unless($item->cart && $item->cart->user_id === $request->user()->id, 403);

        $item->delete();

        return response()->json(['message' => 'Cart item removed.']);
    }
}
