<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Catalog\Services\CatalogService;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(private readonly CatalogService $catalogService) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->catalogService->publicProductsQuery($request)->paginate(20);

        return response()->json($products);
    }

    public function categories(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->filter($request)
            ->orderBy('name', 'asc')
            ->paginate(20);

        return response()->json($categories);
    }
}
