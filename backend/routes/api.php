<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', DashboardController::class);

        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{product}', [ProductController::class, 'show']);

        Route::middleware('role:admin')->group(function () {
            Route::post('products', [ProductController::class, 'store']);
            Route::match(['put', 'patch'], 'products/{product}', [ProductController::class, 'update']);
            Route::delete('products/{product}', [ProductController::class, 'destroy']);
        });
    });
});
