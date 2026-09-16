<?php

namespace App\Domain\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Cache;
use App\Concerns\Concerns\ApiResponse;
use App\Models\Category;
use App\Domain\Catalog\Http\Resources\CategoryResource;
use App\Providers\AppServiceProvider;

class CategoryController extends Controller
{
    use ApiResponse; 

    /**
     * Get all categories
     */
    public function index() 
    {
        $categories = Cache::remember('categories', now()->addHours(24), function () {
            $categories = Category::query()->get();
            return CategoryResource::collection($categories)->toArray(request());
        });


        return $this->successResponse($categories, 'Categories fetched successfully');
    }

    /**
     * Show a category with its products
     */
    public function show(Category $category) 
    {
        $categoryWithProducts = Cache::remember('categories:products:' . $category->id, now()->addMinutes(60), function () use ($category) {
            return new CategoryResource($category->load([
                'products' => fn ($query) => $query->latest()->take(15)
            ]))->toArray(request());
        });

        return $this->successResponse($categoryWithProducts, 'Category fetched successfully');
    }
}
