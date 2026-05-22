<?php

namespace Src\Presentation\Api\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
    public static function success(string $message, array|JsonResource $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data instanceof JsonResource ? $data->resolve(request()) : $data,
            'code' => $code,
        ], $code);
    }

    public static function paginated(string $message, AnonymousResourceCollection $collection, int $code = 200): JsonResponse
    {
        $response = $collection->response()->getData(true);

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
            'links' => $response['links'] ?? [],
            'code' => $code,
        ], $code);
    }

    public static function error(string $message, int $code, ?array $errors = null): JsonResponse
    {
        $payload = [
            'status' => 'error',
            'message' => $message,
            'data' => [],
            'code' => $code,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }
}
