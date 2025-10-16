<?php

use Illuminate\Support\Facades\Route;


Route::prefix('api')->group(function () {
    //Initialisation du prefix API mettre toutes les routes dedans

    Route::get('/', function () {
        return response()->json([
            'message' => "Bienvenue sur l'API Parkly",
            'status' => 'success'
        ]);
    });

    //Fin du prefix API mettre toutes les route avant
});
