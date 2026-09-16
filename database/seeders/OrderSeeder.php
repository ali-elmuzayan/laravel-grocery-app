<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Shipment;
use App\Models\ShipmentEvent;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->firstOrFail();

        $vendor = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->firstOrFail();
        
        $products = Product::all();


        $bananas = $products->where('name', 'Organic Bananas')->first();
        $milk = $products->where('name', 'Whole Milk')->first();


        $subtotal = (float) $bananas->price * 2 + (float) $milk->price;
        $deliveryFee = 4.99;
        $total = $subtotal + $deliveryFee;



        $order = Order::updateOrCreate(
            ['order_number' => 'ORD-DEMO0001'],
            [
                'user_id' => $customer->id,
                'vendor_id' => $vendor->id,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'amount_paid' => $total,
                'payment_status' => 'paid',
                'status' => 'shipped',
                'placed_at' => now()->subDays(3),
            ]
        );

        OrderItem::updateOrCreate(
            [
                'order_id' => $order->id,
                'product_id' => $bananas->id,
            ],
            [
                'product_name' => $bananas->name,
                'quantity' => 2,
                'unit_price' => $bananas->price,
                'line_total' => (float) $bananas->price * 2,
            ]
        );

        OrderItem::updateOrCreate(
            [
                'order_id' => $order->id,
                'product_id' => $milk->id,
            ],
            [
                'product_name' => $milk->name,
                'quantity' => 1,
                'unit_price' => $milk->price,
                'line_total' => $milk->price,
            ]
        );

        OrderStatusHistory::updateOrCreate(
            [
                'order_id' => $order->id,
                'status' => 'pending',
            ],
            [
                'note' => 'Order placed.',
                'changed_by' => $customer->id,
            ]
        );

        OrderStatusHistory::updateOrCreate(
            [
                'order_id' => $order->id,
                'status' => 'shipped',
            ],
            [
                'note' => 'Order shipped to customer.',
                'changed_by' => $vendor->id,
            ]
        );

        $shipment = Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'tracking_number' => 'TRK-DEMO-123456',
                'status' => 'in_transit',
                'carrier' => 'FastRoute Logistics',
                'estimated_delivery_at' => now()->addDay(),
            ]
        );

        $events = [
            [
                'status' => 'label_created',
                'description' => 'Shipping label created.',
                'location' => 'Fresh Farms Warehouse',
                'created_at' => now()->subDays(2),
            ],
            [
                'status' => 'picked_up',
                'description' => 'Package picked up by carrier.',
                'location' => 'Fresh Farms Warehouse',
                'created_at' => now()->subDays(2)->addHours(4),
            ],
            [
                'status' => 'in_transit',
                'description' => 'Package in transit to destination.',
                'location' => 'Regional Distribution Center',
                'created_at' => now()->subDay(),
            ],
        ];

        foreach ($events as $event) {
            ShipmentEvent::updateOrCreate(
                [
                    'shipment_id' => $shipment->id,
                    'status' => $event['status'],
                ],
                [
                    'description' => $event['description'],
                    'location' => $event['location'],
                    'created_at' => $event['created_at'],
                    'updated_at' => $event['created_at'],
                ]
            );
        }
    }
}
