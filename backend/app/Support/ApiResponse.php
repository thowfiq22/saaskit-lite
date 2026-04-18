<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public static function success(
        string $message,
        mixed $data = [],
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    /**
     * @param  array<string, mixed>|list<mixed>  $errors
     */
    public static function error(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public static function paginated(
        LengthAwarePaginator $paginator,
        mixed $data,
        string $message = 'Request completed successfully.'
    ): JsonResponse {
        return self::success($message, $data, 200, [
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ]);
    }
}
