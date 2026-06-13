<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendOrderStatusNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $orderId, public readonly string $status) {}

    public function handle(): void
    {
        $order = Order::query()->find($this->orderId);

        if (! $order) {
            return;
        }

        Log::info('Order status notification queued', [
            'order_id' => $order->id,
            'status' => $this->status,
        ]);
    }
}
