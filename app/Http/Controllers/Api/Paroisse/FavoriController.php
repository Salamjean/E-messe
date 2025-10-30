<?php

namespace App\Http\Controllers\Api\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Favori;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/favoris",
     *     summary="Liste des favoris de l'utilisateur connecté",
     *     description="Récupère la liste complète des paroisses que l'utilisateur a ajoutées à ses favoris.",
     *     tags={"Favoris"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des favoris récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=3),
     *                     @OA\Property(property="paroisse_id", type="integer", example=5),
     *                     @OA\Property(
     *                         property="paroisse",
     *                         type="object",
     *                         @OA\Property(property="nom", type="string", example="Paroisse Saint Michel"),
     *                         @OA\Property(
     *                             property="commune",
     *                             type="object",
     *                             @OA\Property(property="nom", type="string", example="Yopougon"),
     *                             @OA\Property(
     *                                 property="ville",
     *                                 type="object",
     *                                 @OA\Property(property="nom", type="string", example="Abidjan")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/favoris",
     *     summary="Ajouter une paroisse aux favoris",
     *     description="Permet d'ajouter une paroisse à la liste des favoris de l'utilisateur connecté.",
     *     tags={"Favoris"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"paroisse_id"},
     *             @OA\Property(property="paroisse_id", type="integer", example=5, description="ID de la paroisse à ajouter")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paroisse ajoutée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Paroisse ajoutée aux favoris"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="user_id", type="integer", example=3),
     *                 @OA\Property(property="paroisse_id", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Utilisateur non authentifié"),
     *     @OA\Response(response=422, description="Validation échouée (paroisse inexistante)")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/favoris/{id}",
     *     summary="Supprimer un favori",
     *     description="Supprime une paroisse des favoris de l'utilisateur connecté.",
     *     tags={"Favoris"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du favori à supprimer",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favori supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Favori supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Favori introuvable"),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
     * )
     */
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
}
