<?php

namespace App\Concerns\Concerns;

use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    public function successResponse(mixed $data = null, string $message = "success", int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse(string $message = "error", int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    public function notFoundResponse(string $message = "not found")
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }

    public function paginatedSuccessResponse(ResourceCollection $resource, string $message = "success", int $code = 200) 
    {
        $payload = $resource->response()->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $payload['data'],
            'links' => $payload['links'] ?? null,
            'meta' => $payload['meta'] ?? null,
        ], $code);
    }


}
