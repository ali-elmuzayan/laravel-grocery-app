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
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        $vendor = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->firstOrFail();



        $categories = Category::select('id')->get(); 
    

        $categories->map(function ($category) use ($vendor) {
            return Product::factory()->count(100)->create([
                'category_id' => $category->id,
                'vendor_id' => $vendor->id,
                'status' => 'approved',
                'is_active' => true,
            ]); 
        }); 



    }
}
