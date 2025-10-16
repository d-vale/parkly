<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Parking;

class FavoriteController extends Controller
{
    /**
     * GET /api/user/favorites
     * Récupère la liste des parkings favoris de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        // TODO: Récupérer l'utilisateur authentifié
        // $user = $request->user();
        // $userId = $user->id;

        // Pour l'instant, exemple avec un user_id fixe
        $userId = 1;

        // Récupérer les favoris avec les informations des parkings
        $favorites = Favorite::where('user_id', $userId)
            ->with(['parking.owner', 'parking.schedules', 'parking.prices'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favorites->map(function($favorite) {
                return [
                    'favorite_id' => $favorite->id,
                    'parking' => $favorite->parking,
                    'added_at' => $favorite->created_at
                ];
            }),
            'count' => $favorites->count()
        ]);
    }
}
