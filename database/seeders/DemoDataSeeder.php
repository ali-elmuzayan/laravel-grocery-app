<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductApproval;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\WalletAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    private const PASSWORD = 'password';

    public function run(): void
    {
        $admin = $this->createUser(
            name: 'Admin User',
            email: 'admin@grocery.test',
            role: 'admin',
            phone: '+15550000001',
        );

        $vendor = $this->createUser(
            name: 'Fresh Farms Vendor',
            email: 'vendor@grocery.test',
            role: 'vendor',
            phone: '+15550000002',
        );

        $customer = $this->createUser(
            name: 'Jane Customer',
            email: 'customer@grocery.test',
            role: 'user',
            phone: '+15550000003',
        );

        VendorProfile::updateOrCreate(
            ['user_id' => $vendor->id],
            [
                'business_name' => 'Fresh Farms Market',
                'status' => 'approved',
                'rating' => 4.85,
            ]
        );

        WalletAccount::updateOrCreate(
            ['user_id' => $vendor->id],
            [
                'currency' => 'USD',
                'available_balance' => 250.00,
                'pending_balance' => 75.50,
            ]
        );

        $categories = $this->seedCategories($admin);

        $products = $this->seedProducts($vendor, $admin, $categories);

        $this->seedSampleOrder($customer, $vendor, $products['organic-bananas'], $products['whole-milk']);
    }

    private function createUser(string $name, string $email, string $role, ?string $phone = null): User
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make(self::PASSWORD),
                'phone' => $phone,
                'status' => 'active',
                'email_verified_at' => now(),
                'is_identity_verified' => true,
            ]
        );

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }

        return $user;
    }

    /**
     * @return array<string, Category>
     */
    private function seedCategories(User $admin): array
    {
        $definitions = [
            'fruits-vegetables' => [
                'name' => 'Fruits & Vegetables',
                'description' => 'Fresh produce delivered daily.',
            ],
            'dairy-eggs' => [
                'name' => 'Dairy & Eggs',
                'description' => 'Milk, cheese, yogurt, and eggs.',
            ],
            'bakery' => [
                'name' => 'Bakery',
                'description' => 'Fresh bread, pastries, and baked goods.',
            ],
            'beverages' => [
                'name' => 'Beverages',
                'description' => 'Juices, coffee, tea, and soft drinks.',
            ],
            'pantry-staples' => [
                'name' => 'Pantry Staples',
                'description' => 'Rice, pasta, oils, and dry goods.',
            ],
        ];

        $categories = [];

        foreach ($definitions as $slug => $data) {
            $categories[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'created_by' => $admin->id,
                ]
            );
        }

        return $categories;
    }

    /**
     * @param  array<string, Category>  $categories
     * @return array<string, Product>
     */
    private function seedProducts(User $vendor, User $admin, array $categories): array
    {
        $definitions = [
            'organic-bananas' => [
                'category' => 'fruits-vegetables',
                'name' => 'Organic Bananas',
                'description' => 'Bunch of ripe organic bananas.',
                'price' => 1.99,
                'stock' => 120,
                'status' => 'approved',
                'is_active' => true,
                'variants' => [
                    ['sku' => 'BAN-ORG-1LB', 'name' => '1 lb bunch', 'price' => 1.99, 'stock' => 60],
                    ['sku' => 'BAN-ORG-2LB', 'name' => '2 lb bunch', 'price' => 3.49, 'stock' => 60],
                ],
            ],
            'whole-milk' => [
                'category' => 'dairy-eggs',
                'name' => 'Whole Milk',
                'description' => 'Farm-fresh whole milk, 1 gallon.',
                'price' => 3.49,
                'stock' => 80,
                'status' => 'approved',
                'is_active' => true,
                'variants' => [],
            ],
            'sourdough-bread' => [
                'category' => 'bakery',
                'name' => 'Sourdough Bread',
                'description' => 'Handcrafted sourdough loaf.',
                'price' => 4.99,
                'stock' => 45,
                'status' => 'approved',
                'is_active' => true,
                'variants' => [],
            ],
            'orange-juice' => [
                'category' => 'beverages',
                'name' => 'Fresh Orange Juice',
                'description' => 'Cold-pressed orange juice, 64 oz.',
                'price' => 5.99,
                'stock' => 55,
                'status' => 'approved',
                'is_active' => true,
                'variants' => [],
            ],
            'basmati-rice' => [
                'category' => 'pantry-staples',
                'name' => 'Basmati Rice 5 lb',
                'description' => 'Premium long-grain basmati rice.',
                'price' => 12.99,
                'stock' => 35,
                'status' => 'approved',
                'is_active' => true,
                'variants' => [],
            ],
            'artisan-cheese' => [
                'category' => 'dairy-eggs',
                'name' => 'Artisan Cheese Wheel',
                'description' => 'Aged artisan cheese awaiting admin approval.',
                'price' => 18.50,
                'stock' => 12,
                'status' => 'pending',
                'is_active' => false,
                'variants' => [],
            ],
        ];

        $products = [];

        foreach ($definitions as $slug => $data) {
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'vendor_id' => $vendor->id,
                    'category_id' => $categories[$data['category']]->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                    'status' => $data['status'],
                    'is_active' => $data['is_active'],
                ]
            );

            if ($product->status === 'approved') {
                ProductApproval::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'status' => 'approved',
                    ],
                    [
                        'reviewed_by' => $admin->id,
                        'reviewed_at' => now()->subDays(2),
                        'note' => 'Seeded approved product.',
                    ]
                );
            }

            foreach ($data['variants'] as $variant) {
                ProductVariant::updateOrCreate(
                    ['sku' => $variant['sku']],
                    [
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                    ]
                );
            }

            $products[$slug] = $product;
        }

        return $products;
    }

    private function seedSampleOrder(User $customer, User $vendor, Product $bananas, Product $milk): void
    {
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
