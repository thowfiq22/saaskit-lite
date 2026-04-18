<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'SaaSKit Lite API',
        'version' => 'v1.0.0',
        'documentation' => 'See the repository README and docs folder for setup and API details.',
        'status' => 'ok',
    ]);
});
