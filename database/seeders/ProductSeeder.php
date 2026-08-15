<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductApproval;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    private array $definitions = [
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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $vendor = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->firstOrFail();


        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->firstOrFail();

        $categories = Category::all();

        
        $products = [];

        foreach ($this->definitions as $slug => $data) {
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

    }
}
