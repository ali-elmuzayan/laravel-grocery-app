<?php

namespace App\Domain\Cart\Services;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    public function activeCartForUser(int $userId): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $userId,
            'status' => 'active',
        ]);
    }

    public function addItem(Cart $cart, Product $product, int $quantity = 1): Cart
    {
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $quantity;
        $item->unit_price = $product->price;
        $item->save();

        return $cart->load('items.product');
    }
}
