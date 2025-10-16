<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ParkingController;
use App\Http\Controllers\Api\FavoriteController;


Route::prefix('api')->group(function () {

    //Initialisation du prefix API mettre toutes les routes dedans
    Route::get('/', function () {
        return response()->json([
            'message' => "Bienvenue sur l'API Parkly",
            'status' => 'success'
        ]);
    });

    // USER
    Route::get('/user', [UserController::class, 'show'])->name('api.user.show');
    Route::patch('/user', [UserController::class, 'update'])->name('api.user.update');

    // PARKINGS
    Route::get('/parkings', [ParkingController::class, 'index'])->name('api.parkings.index');
    Route::get('/parkings/{parking_id}/infos', [ParkingController::class, 'infos'])->name('api.parkings.infos');
    Route::get('/parkings/{parking_id}/schema', [ParkingController::class, 'schema'])->name('api.parkings.schema');

    // FAVORITES
    Route::get('/user/favorites', [FavoriteController::class, 'index'])->name('api.user.favorites');

    //Fin du prefix API mettre toutes les route avant
});
