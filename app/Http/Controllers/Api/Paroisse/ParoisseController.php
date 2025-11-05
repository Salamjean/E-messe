<?php

namespace App\Http\Controllers\Api\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use App\Models\Favori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParoisseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/paroisses",
     *     summary="Lister les paroisses avec filtres et pagination",
     *     description="Permet de récupérer une liste paginée des paroisses avec des filtres optionnels (recherche, ville, commune) et indique les favoris de l’utilisateur connecté.",
     *     tags={"Paroisses"},
     *     @OA\Parameter(name="search", in="query", description="Recherche par nom de paroisse", @OA\Schema(type="string")),
     *     @OA\Parameter(name="ville", in="query", description="Nom partiel ou complet de la ville", @OA\Schema(type="string")),
     *     @OA\Parameter(name="commune", in="query", description="Nom partiel ou complet de la commune", @OA\Schema(type="string")),
     *     @OA\Parameter(name="per_page", in="query", description="Nombre d’éléments par page (par défaut 10)", @OA\Schema(type="integer", example=10)),
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée des paroisses récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Liste des paroisses récupérée avec succès"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        // 🔍 Construction de la requête
        $query = Paroisse::with(['commune.ville', 'messes']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('ville')) {
            $query->whereHas('commune.ville', function ($q) use ($request) {
                $q->where('nom_ville', 'like', '%' . $request->ville . '%');
            });
        }

        if ($request->filled('commune')) {
            $query->whereHas('commune', function ($q) use ($request) {
                $q->where('nom_commune', 'like', '%' . $request->commune . '%');
            });
        }

        // 📄 Pagination
        $perPage = $request->get('per_page', 10);
        $paroisses = $query->paginate($perPage);

        // ⭐ Favoris utilisateur
        $favoris = Auth::check()
            ? Favori::where('user_id', Auth::id())->pluck('paroisse_id')->toArray()
            : [];

        // 🧩 Transformation des données
        $paroisses->getCollection()->transform(function ($paroisse) use ($favoris) {
            $montantTotal = $paroisse->messes->sum('montant_offrande');
            $montantMoyen = $paroisse->messes->count() > 0
                ? round($paroisse->messes->avg('montant_offrande'), 2)
                : 0;

            $profilePictureUrl = $paroisse->profile_picture
                ? asset('storage/paroisses/' . $paroisse->profile_picture)
                : null;

            return [
                'id' => $paroisse->id,
                'name' => $paroisse->name,
                'email' => $paroisse->email,
                'contact' => $paroisse->contact,
                'profile_picture' => $profilePictureUrl,
                'commune' => $paroisse->commune->nom_commune ?? null,
                'ville' => $paroisse->commune->ville->nom_ville ?? null,
                'montant_total_messes' => $montantTotal,
                'montant_moyen_messes' => $montantMoyen,
                'is_favori' => in_array($paroisse->id, $favoris),
            ];
        });

        // ✅ Réponse JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Liste des paroisses récupérée avec succès',
            'data' => $paroisses,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/paroisses/{id}",
     *     summary="Afficher les détails d’une paroisse",
     *     tags={"Paroisses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant unique de la paroisse",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la paroisse",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Paroisse introuvable")
     * )
     */
    public function show($id): JsonResponse
    {
        // 🔍 Récupération de la paroisse avec ses relations
        $paroisse = Paroisse::with(['commune.ville', 'messes'])->findOrFail($id);

        // ⭐ Vérification du favori
        $isFavori = Auth::check() && Favori::where('user_id', Auth::id())
            ->where('paroisse_id', $paroisse->id)
            ->exists();

        // 💰 Calculs
        $montantTotal = $paroisse->messes->sum('montant_offrande');
        $montantMoyen = $paroisse->messes->count() > 0
            ? round($paroisse->messes->avg('montant_offrande'), 2)
            : 0;

        // 🖼️ Image de profil
        $profilePictureUrl = $paroisse->profile_picture
            ? asset('storage/paroisses/' . $paroisse->profile_picture)
            : null;

        // 🧩 Données formatées
        $data = [
            'id' => $paroisse->id,
            'name' => $paroisse->name,
            'email' => $paroisse->email,
            'contact' => $paroisse->contact,
            'profile_picture' => $profilePictureUrl,
            'commune' => $paroisse->commune->nom_commune ?? null,
            'ville' => $paroisse->commune->ville->nom_ville ?? null,
            'montant_total_messes' => $montantTotal,
            'montant_moyen_messes' => $montantMoyen,
            'is_favori' => $isFavori,
        ];

        // ✅ Réponse JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Détails de la paroisse récupérés avec succès',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/paroisses/recommandations",
     *     summary="Obtenir les paroisses recommandées",
     *     description="Retourne les 5 paroisses les plus actives basées sur le nombre de messes et le montant total.",
     *     tags={"Paroisses"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des paroisses recommandées",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Paroisse"))
     *     )
     * )
     */
    public function recommandations(): JsonResponse
    {
        $paroisses = Paroisse::withCount('messes')
            ->with(['commune.ville'])
            ->orderByDesc('messes_count')
            ->take(5)
            ->get()
            ->each(function ($paroisse) {
                $paroisse->montant_total_messes = $paroisse->messes->sum('montant_offrande');
                $paroisse->score_popularite = $paroisse->messes_count + ($paroisse->montant_total_messes / 1000);
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Liste des paroisses recommandées',
            'data' => $paroisses,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/paroisses/{paroisse_id}/favori",
     *     summary="Vérifier si une paroisse est dans les favoris",
     *     tags={"Paroisses"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="paroisse_id",
     *         in="path",
     *         required=true,
     *         description="ID de la paroisse",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(response=200, description="Statut du favori renvoyé"),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
     * )
     */
    public function checkFavori($paroisse_id): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié.',
            ], 401);
        }

        $isFavori = Favori::where('user_id', $user->id)
            ->where('paroisse_id', $paroisse_id)
            ->exists();

        return response()->json([
            'status' => 'success',
            'paroisse_id' => $paroisse_id,
            'is_favori' => $isFavori,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/paroisses/favori/toggle",
     *     summary="Ajouter ou retirer une paroisse des favoris",
     *     tags={"Paroisses"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(@OA\Property(property="paroisse_id", type="integer", example=2))
     *     ),
     *     @OA\Response(response=200, description="Action sur le favori effectuée"),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
     * )
     */
    public function toggleFavori(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'paroisse_id' => 'required|exists:paroisses,id',
        ]);

        $user = Auth::user();

        $favori = Favori::where('user_id', $user->id)
            ->where('paroisse_id', $validated['paroisse_id'])
            ->first();

        if ($favori) {
            $favori->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Paroisse retirée des favoris',
            ]);
        }

        $favori = Favori::create([
            'user_id' => $user->id,
            'paroisse_id' => $validated['paroisse_id'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Paroisse ajoutée aux favoris',
            'data' => $favori,
        ]);
    }
}
