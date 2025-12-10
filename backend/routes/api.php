<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ParkingController;
use App\Http\Controllers\Api\FavoriteController;

// ============================================================================
// ROUTES PUBLIQUES (Pas d'authentification requise)
// ============================================================================

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::get('/check', [AuthController::class, 'check'])->name('api.check');

// ============================================================================
// ROUTES PROTÉGÉES (Authentification requise)
// ============================================================================

Route::middleware(['auth:sanctum'])->group(function () {

    // ========================================
    // AUTHENTIFICATION
    // ========================================
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');

    // ========================================
    // PROFIL UTILISATEUR
    // ========================================
    Route::patch('/user', [UserController::class, 'update'])->name('api.user.update');

    // ========================================
    // PARKINGS
    // ========================================
    Route::prefix('parkings')->name('api.parkings.')->group(function () {
        Route::get('/', [ParkingController::class, 'index'])->name('index');
        Route::get('/{parking_id}/infos', [ParkingController::class, 'infos'])->name('infos');
        Route::get('/{parking_id}/schema', [ParkingController::class, 'schema'])->name('schema');
    });

    // ========================================
    // FAVORIS
    // ========================================
    Route::prefix('user/favorites')->name('api.favorites.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        Route::post('/{parking_id}', [FavoriteController::class, 'store'])->name('store');
        Route::delete('/{parking_id}', [FavoriteController::class, 'destroy'])->name('destroy');
    });
});
