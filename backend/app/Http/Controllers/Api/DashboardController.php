<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardStatsResource;
use App\Services\DashboardService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success('Dashboard statistics fetched successfully.', [
            'stats' => DashboardStatsResource::make(
                $this->dashboardService->overview($request->user())
            ),
        ]);
    }
}
