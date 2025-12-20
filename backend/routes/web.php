<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SwaggerController;

// Health check endpoint
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Parkly API is running',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Swagger Documentation Routes
Route::get('/api/docs', [SwaggerController::class, 'docs'])->name('swagger.docs');
Route::get('/api/openapi.yaml', [SwaggerController::class, 'yaml'])->name('swagger.yaml');
