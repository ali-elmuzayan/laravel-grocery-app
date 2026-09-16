<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'slug' => fake()->slug(),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 10, 100),
            'stock' => fake()->numberBetween(1, 100),
            'discount' => fake()->numberBetween(0, 100),
            'discount_expires_at' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => 'approved',
            'is_active' => true,
            'vendor_id' => '3',
            'category_id' => '1',
        ];
    }
}
