<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ParkingController;
use App\Http\Controllers\Api\FavoriteController;

Route::prefix('api')->group(function () {

    // Routes publiques (pas besoin d'authentification)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check', [AuthController::class, 'check']);

    // Routes protégées (nécessitent l'authentification)
    Route::middleware('auth:sanctum')->group(function () {

        // PARKING
        Route::get('/parkings', [ParkingController::class, 'index']);
        Route::get('/parkings/{parking_id}/infos', [ParkingController::class, 'infos']);
        Route::get('/parkings/{parking_id}/schema', [ParkingController::class, 'schema']);

        // AUTH
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // USER
        Route::patch('/user', [UserController::class, 'update']);

        // FAVORITES
        Route::get('/user/favorites', [FavoriteController::class, 'index']);
        Route::post('/user/favorites/{parking_id}', [FavoriteController::class, 'store']);
        Route::delete('/user/favorites/{parking_id}', [FavoriteController::class, 'destroy']);
    });
});
