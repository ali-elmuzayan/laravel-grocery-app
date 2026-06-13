<?php

namespace App\Domain\Orders\Services;

use App\Jobs\SendOrderStatusNotification;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function checkout(Cart $cart): Order
    {
        return DB::transaction(function () use ($cart): Order {
            $cart->loadMissing('items.product');

            if ($cart->items->isEmpty()) {
                abort(422, 'Cart is empty.');
            }

            $vendorId = $cart->items->first()->product->vendor_id;

            $subtotal = $cart->items->sum(fn ($item) => $item->quantity * (float) $item->unit_price);
            $deliveryFee = 0;
            $total = $subtotal + $deliveryFee;

            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                'user_id' => $cart->user_id,
                'vendor_id' => $vendorId,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'amount_paid' => 0,
                'payment_status' => 'pending',
                'status' => 'pending',
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->quantity * (float) $item->unit_price,
                ]);
            }

            $order->statusHistories()->create([
                'status' => 'pending',
                'note' => 'Order created from cart checkout.',
                'changed_by' => $cart->user_id,
            ]);

            $cart->update(['status' => 'checked_out']);
            $cart->items()->delete();

            SendOrderStatusNotification::dispatch($order->id, 'pending');

            return $order;
        });
    }
}
