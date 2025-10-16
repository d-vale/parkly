<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * GET /api/user
     * Récupère les informations de l'utilisateur connecté
     */
    public function show(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = $request->user();

        // TODO: Ajouter l'authentification (Sanctum, Passport, etc.)
        // Pour l'instant, retourne un exemple

        return response()->json([
            'success' => true,
            'data' => $user ?? [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * PATCH /api/user
     * Met à jour les informations de l'utilisateur connecté
     */
    public function update(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        // TODO: Récupérer l'utilisateur authentifié et le mettre à jour
        // $user = $request->user();
        // $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => [
                'id' => 1,
                'name' => $validated['name'] ?? 'John Doe',
                'email' => $validated['email'] ?? 'john@example.com',
            ]
        ]);
    }
}
