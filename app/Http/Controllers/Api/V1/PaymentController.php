<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Payments\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentIntent;
use App\Models\PayoutRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function createIntent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'idempotency_key' => ['required', 'string', 'max:120'],
        ]);

        $order = Order::query()->findOrFail($data['order_id']);
        abort_unless($order->user_id === $request->user()->id, 403);

        if ((float) $data['amount'] < (float) $order->total && ! $request->user()->is_identity_verified) {
            abort(422, 'Partial payment is available only for identity-verified users.');
        }

        $intent = $this->paymentService->createIntent($order, (float) $data['amount'], $data['idempotency_key']);

        return response()->json($intent, 201);
    }

    public function captureIntent(PaymentIntent $paymentIntent): JsonResponse
    {
        $intent = $this->paymentService->captureIntent($paymentIntent);

        return response()->json($intent);
    }

    public function requestPayout(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        abort_unless($request->user()->hasAnyRole(['vendor', 'admin']), 403);

        $payout = PayoutRequest::create([
            'vendor_id' => $request->user()->id,
            'amount' => $request->float('amount'),
            'note' => $request->string('note')->toString(),
            'status' => 'pending',
        ]);

        return response()->json($payout, 201);
    }
}
