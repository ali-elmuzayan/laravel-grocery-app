<?php

namespace App\Domain\Catalog\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class CatalogService
{
    public function publicProductsQuery(): Builder
    {
        return Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->latest();
    }
}
