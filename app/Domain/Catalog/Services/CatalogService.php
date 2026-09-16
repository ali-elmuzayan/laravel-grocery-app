<?php

namespace App\Domain\Catalog\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CatalogService
{
    public function publicProductsQuery(Request $request): Builder
    {
        return Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->filter($request)
            ->orderBy('name', 'asc');
    }
}
