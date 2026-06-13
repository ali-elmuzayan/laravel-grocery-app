<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Models\Order;
use App\Models\PaymentIntent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPaymentReceiptEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $orderId, public readonly int $paymentIntentId) {}

    public function handle(): void
    {
        $order = Order::query()->find($this->orderId);
        $intent = PaymentIntent::query()->find($this->paymentIntentId);

        if (! $order || ! $intent) {
            return;
        }

        EmailLog::create([
            'user_id' => $order->user_id,
            'type' => 'payment_receipt',
            'recipient_email' => $order->user->email,
            'status' => 'queued',
            'meta' => [
                'order_id' => $order->id,
                'payment_intent_id' => $intent->id,
            ],
        ]);
    }
}
