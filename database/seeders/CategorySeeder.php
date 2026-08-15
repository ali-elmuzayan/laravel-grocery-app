<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    private array         $definitions = [
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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin where role is admin    
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->firstOrFail();
        $categories = [];

        foreach ($this->definitions as $slug => $data) {
            $categories[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'created_by' => $admin->id,
                ]
            );
        }

    }
}
