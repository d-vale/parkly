<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Parking;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    /**
     * GET /api/user/favorites
     * Récupère la liste des parkings favoris de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Récupérer les favoris avec les informations des parkings
        $favorites = $user->favorites()
            ->with(['owner', 'schedule', 'price'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favorites->map(function ($parking) {
                return [
                    'id' => $parking->id,
                    'name' => $parking->name,
                    'city' => $parking->city,
                    'postal_code' => $parking->postal_code,
                    'address' => $parking->address,
                    'type' => $parking->type,
                    'owner' => $parking->owner,
                    'schedule' => $parking->schedule,
                    'price' => $parking->price,
                    'available_spots_count' => $parking->available_spots_count,
                    'total_spots_count' => $parking->total_spots_count,
                    'added_at' => $parking->pivot->created_at,
                ];
            }),
            'count' => $favorites->count(),
        ]);
    }

    /**
     * POST /api/user/favorites/{parking_id}
     * Ajoute un parking aux favoris de l'utilisateur
     */
    public function store(Request $request, int $parkingId)
    {
        $user = $request->user();

        // Vérifier que le parking existe
        $parking = Parking::find($parkingId);

        if (!$parking) {
            return response()->json([
                'success' => false,
                'message' => 'Parking introuvable',
            ], 404);
        }

        // Vérifier si le parking est déjà en favoris
        if ($user->favorites()->where('parking_id', $parkingId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce parking est déjà dans vos favoris',
            ], 409); // 409 Conflict
        }

        // Ajouter aux favoris
        $user->favorites()->attach($parkingId);

        return response()->json([
            'success' => true,
            'message' => 'Parking ajouté aux favoris avec succès',
            'data' => [
                'parking_id' => $parkingId,
                'parking_name' => $parking->name,
            ],
        ], 201);
    }

    /**
     * DELETE /api/user/favorites/{parking_id}
     * Retire un parking des favoris de l'utilisateur
     */
    public function destroy(Request $request, int $parkingId)
    {
        $user = $request->user();

        // Vérifier que le parking existe
        $parking = Parking::find($parkingId);

        if (!$parking) {
            return response()->json([
                'success' => false,
                'message' => 'Parking introuvable',
            ], 404);
        }

        // Vérifier si le parking est dans les favoris
        if (!$user->favorites()->where('parking_id', $parkingId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce parking n\'est pas dans vos favoris',
            ], 404);
        }

        // Retirer des favoris
        $user->favorites()->detach($parkingId);

        return response()->json([
            'success' => true,
            'message' => 'Parking retiré des favoris avec succès',
            'data' => [
                'parking_id' => $parkingId,
                'parking_name' => $parking->name,
            ],
        ]);
    }
}
