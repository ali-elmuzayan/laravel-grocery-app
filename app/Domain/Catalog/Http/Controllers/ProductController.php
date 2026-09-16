<?php

namespace App\Domain\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Domain\Catalog\Http\Resources\ProductResource;
use App\Models\Product;
use App\Concerns\Concerns\ApiResponse;

class ProductController extends Controller
{
    use ApiResponse; 
    public int $perPage; 

    /**
     * Fetch all products 
     */
    public function index() 
    {
        $perPage = min(
            max(request()->integer('per_page', 10), 1),
            100
        );
        $products = Product::query()
            ->latest('created_at')
            ->paginate($perPage);



        return $this->paginatedSuccessResponse(ProductResource::collection($products), 'Products fetched successfully');
    }

    /**
     * Fetch a single product by id
     */
    public function show(Product $product) 
    {
        return $this->successResponse(new ProductResource($product), 'Product fetched successfully');
    }
}