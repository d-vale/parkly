<?php

use Illuminate\Support\Facades\Route;

// Health check endpoint
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Parkly API is running',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});
