<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function timeline(Request $request, Order $order): JsonResponse
    {
        abort_unless($request->user()->can('view-order', $order), 403);

        $shipment = $order->shipment()->with('events')->first();

        return response()->json([
            'order_id' => $order->id,
            'shipment' => $shipment,
            'events' => $shipment?->events ?? [],
        ]);
    }
}
