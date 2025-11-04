<?php

namespace App\Http\Controllers\Api\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Favori;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    public function index(): JsonResponse
    {
        $favoris = Favori::with('paroisse.commune.ville')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $favoris
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $validated = $request->validate([
            'paroisse_id' => 'required|exists:paroisses,id',
        ]);

        $favori = Favori::firstOrCreate([
            'user_id' => Auth::id(),
            'paroisse_id' => $validated['paroisse_id'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Paroisse ajoutée aux favoris',
            'data' => $favori
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $favori = Favori::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $favori->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Favori supprimé avec succès'
        ]);
    }

    /**
     * Vérifie si une paroisse est dans les favoris de l'utilisateur
     */
    public function check($id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $exists = Favori::where('user_id', Auth::id())
            ->where('paroisse_id', $id)
            ->exists();

        return response()->json([
            'status' => 'success',
            'favori' => $exists
        ]);
    }
}
