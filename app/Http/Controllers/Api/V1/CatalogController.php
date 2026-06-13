<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Catalog\Services\CatalogService;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function __construct(private readonly CatalogService $catalogService) {}

    public function index(): JsonResponse
    {
        $products = $this->catalogService->publicProductsQuery()->paginate(20);

        return response()->json($products);
    }

    public function categories(): JsonResponse
    {
        return response()->json(Category::query()->orderBy('name')->get());
    }
}
