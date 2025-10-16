<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parking;
use App\Models\Spot;
use App\Models\Floor;

class ParkingController extends Controller
{
    /**
     * GET /api/parkings
     * Récupère la liste de tous les parkings avec leurs informations de base
     */
    public function index()
    {
        // Récupérer tous les parkings avec les relations nécessaires
        $parkings = Parking::with(['owner', 'schedule', 'price'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $parkings,
            'count' => $parkings->count()
        ]);
    }

    /**
     * GET /api/parkings/{parking_id}/infos
     * Récupère les informations détaillées d'un parking spécifique
     */
    public function infos($parking_id)
    {
        // Récupérer le parking avec toutes ses relations
        $parking = Parking::with([
            'owner',
            'schedule',
            'price',
            'floors.spots',
        ])->find($parking_id);

        if (!$parking) {
            return response()->json([
                'success' => false,
                'message' => 'Parking non trouvé'
            ], 404);
        }

        // Calculer les statistiques
        $totalSpots = Spot::where('parking_id', $parking_id)->count();
        $availableSpots = Spot::where('parking_id', $parking_id)
            ->where('is_available', true)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'parking' => $parking,
                'statistics' => [
                    'total_spots' => $totalSpots,
                    'available_spots' => $availableSpots,
                    'occupancy_rate' => $totalSpots > 0 ?
                        round((($totalSpots - $availableSpots) / $totalSpots) * 100, 2) : 0
                ]
            ]
        ]);
    }

    /**
     * GET /api/parkings/{parking_id}/schema
     * Récupère le schéma/plan du parking avec tous les étages et places
     */
    public function schema($parking_id)
    {
        // Vérifier que le parking existe
        $parking = Parking::find($parking_id);

        if (!$parking) {
            return response()->json([
                'success' => false,
                'message' => 'Parking non trouvé'
            ], 404);
        }

        // Récupérer tous les étages avec leurs places
        $floors = Floor::where('parking_id', $parking_id)
            ->with(['spots' => function($query) {
                $query->orderBy('spot_number');
            }])
            ->orderBy('level')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'parking_id' => $parking_id,
                'parking_name' => $parking->name,
                'floors' => $floors->map(function($floor) {
                    return [
                        'floor_id' => $floor->id,
                        'level' => $floor->level,
                        'name' => $floor->name,
                        'spots' => $floor->spots->map(function($spot) {
                            return [
                                'spot_id' => $spot->id,
                                'spot_number' => $spot->spot_number,
                                'is_available' => $spot->is_available,
                                'spot_type' => $spot->spot_type,
                                'position' => [
                                    'x' => $spot->position_x ?? 0,
                                    'y' => $spot->position_y ?? 0
                                ]
                            ];
                        })
                    ];
                })
            ]
        ]);
    }
}
