<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductApproval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function storeCategory(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name'].'-'.Str::random(6)),
            'description' => $data['description'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return response()->json($category, 201);
    }

    public function approveProduct(Request $request, Product $product): JsonResponse
    {
        $product->update(['status' => 'approved', 'is_active' => true]);

        ProductApproval::create([
            'product_id' => $product->id,
            'reviewed_by' => $request->user()->id,
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        return response()->json(['message' => 'Product approved.']);
    }

    public function rejectProduct(Request $request, Product $product): JsonResponse
    {
        $product->update(['status' => 'rejected', 'is_active' => false]);

        ProductApproval::create([
            'product_id' => $product->id,
            'reviewed_by' => $request->user()->id,
            'status' => 'rejected',
            'note' => $request->string('note')->toString(),
            'reviewed_at' => now(),
        ]);

        return response()->json(['message' => 'Product rejected.']);
    }
}
