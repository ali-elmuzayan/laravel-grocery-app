<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['vendor_id', 'category_id', 'name', 'slug', 'description', 'price', 'stock', 'status', 'is_active'])]
class Product extends Model
{
    use Filterable, HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationships with Category model
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
