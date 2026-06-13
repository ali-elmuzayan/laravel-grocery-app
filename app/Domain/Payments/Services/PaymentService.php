<?php

namespace App\Domain\Payments\Services;

use App\Jobs\SendPaymentReceiptEmail;
use App\Models\EscrowHold;
use App\Models\Order;
use App\Models\PaymentIntent;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function createIntent(Order $order, float $amount, string $idempotencyKey): PaymentIntent
    {
        return PaymentIntent::firstOrCreate(
            ['idempotency_key' => $idempotencyKey],
            [
                'order_id' => $order->id,
                'amount' => $amount,
                'status' => 'pending',
            ]
        );
    }

    public function captureIntent(PaymentIntent $intent): PaymentIntent
    {
        return DB::transaction(function () use ($intent): PaymentIntent {
            if ($intent->status === 'captured') {
                return $intent;
            }

            $order = $intent->order()->lockForUpdate()->firstOrFail();
            $order->amount_paid = (float) $order->amount_paid + (float) $intent->amount;
            $order->payment_status = $order->amount_paid >= $order->total ? 'paid' : 'partial';
            $order->save();

            $intent->update([
                'provider_reference' => 'PAY-'.strtoupper(Str::random(12)),
                'status' => 'captured',
                'captured_at' => now(),
            ]);

            EscrowHold::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'vendor_id' => $order->vendor_id,
                    'amount' => $intent->amount,
                    'release_at' => now()->addDays(7),
                    'status' => 'held',
                ]
            );

            $wallet = WalletAccount::firstOrCreate(['user_id' => $order->vendor_id], [
                'currency' => 'USD',
                'available_balance' => 0,
                'pending_balance' => 0,
            ]);

            $wallet->pending_balance = (float) $wallet->pending_balance + (float) $intent->amount;
            $wallet->save();

            WalletTransaction::create([
                'wallet_account_id' => $wallet->id,
                'reference' => 'WAL-'.strtoupper(Str::random(12)),
                'type' => 'escrow_hold',
                'amount' => $intent->amount,
                'status' => 'posted',
                'meta' => ['order_id' => $order->id],
            ]);

            SendPaymentReceiptEmail::dispatch($order->id, $intent->id);

            return $intent->refresh();
        });
    }
}
