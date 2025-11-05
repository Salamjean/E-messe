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
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche par nom de paroisse",
     *         required=false,
     *         @OA\Schema(type="string", example="Saint Michel")
     *     ),
     *     @OA\Parameter(
     *         name="ville",
     *         in="query",
     *         description="Nom partiel ou complet de la ville",
     *         required=false,
     *         @OA\Schema(type="string", example="Abidjan")
     *     ),
     *     @OA\Parameter(
     *         name="commune",
     *         in="query",
     *         description="Nom partiel ou complet de la commune",
     *         required=false,
     *         @OA\Schema(type="string", example="Yopougon")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d’éléments par page (par défaut 10)",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée des paroisses récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Liste des paroisses récupérée avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Paroisse Saint Michel"),
     *                         @OA\Property(property="email", type="string", example="saintmichel@eglise.ci"),
     *                         @OA\Property(property="contact", type="string", example="+2250700000000"),
     *                         @OA\Property(property="commune", type="string", example="Yopougon"),
     *                         @OA\Property(property="ville", type="string", example="Abidjan"),
     *                         @OA\Property(property="montant_total_messes", type="number", example=25000),
     *                         @OA\Property(property="montant_moyen_messes", type="number", example=5000),
     *                         @OA\Property(property="is_favori", type="boolean", example=true)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
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

        $perPage = $request->get('per_page', 10);
        $paroisses = $query->paginate($perPage);

        $favoris = Auth::check()
            ? Favori::where('user_id', Auth::id())->pluck('paroisse_id')->toArray()
            : [];

        $paroisses->getCollection()->transform(function ($paroisse) use ($favoris) {
            $montantTotal = $paroisse->messes->sum('montant_offrande');
            $montantMoyen = $paroisse->messes->count() > 0 ? round($paroisse->messes->avg('montant_offrande'), 2) : 0;

            return [
                'id' => $paroisse->id,
                'name' => $paroisse->name,
                'email' => $paroisse->email,
                'contact' => $paroisse->contact,
                'commune' => $paroisse->commune->nom_commune ?? null,
                'ville' => $paroisse->commune->ville->nom_ville ?? null,
                'montant_total_messes' => $montantTotal,
                'montant_moyen_messes' => $montantMoyen,
                'is_favori' => in_array($paroisse->id, $favoris),
            ];
        });

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
     *         description="Identifiant unique de la paroisse",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la paroisse",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Paroisse Saint Michel"),
     *                 @OA\Property(property="email", type="string", example="paroisse@eglise.ci"),
     *                 @OA\Property(property="contact", type="string", example="+2250700000000"),
     *                 @OA\Property(property="commune", type="string", example="Yopougon"),
     *                 @OA\Property(property="ville", type="string", example="Abidjan"),
     *                 @OA\Property(property="montant_total_messes", type="number", example=50000),
     *                 @OA\Property(property="montant_moyen_messes", type="number", example=10000),
     *                 @OA\Property(property="is_favori", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Paroisse introuvable")
     * )
     */
    public function show($id): JsonResponse
    {
        $paroisse = Paroisse::with(['commune.ville', 'messes'])->findOrFail($id);

        // Récupération des favoris de l'utilisateur connecté (si connecté)
        $favoris = Auth::check()
            ? Favori::where('user_id', Auth::id())->pluck('paroisse_id')->toArray()
            : [];

        // Calcul des montants
        $montantTotal = $paroisse->messes->sum('montant_offrande');
        $montantMoyen = $paroisse->messes->count() > 0
            ? round($paroisse->messes->avg('montant_offrande'), 2)
            : 0;

        // Génération de l'URL complète de la photo de profil (si elle existe)
        $profilePictureUrl = $paroisse->profile_picture
            ? asset('storage/paroisses/' . $paroisse->profile_picture)
            : null;

        // Structuration de la réponse
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
            'is_favori' => in_array($paroisse->id, $favoris),
        ];

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
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Liste des paroisses recommandées"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Paroisse")
     *             )
     *         )
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
     *     @OA\Response(
     *         response=200,
     *         description="Statut du favori renvoyé",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="paroisse_id", type="integer", example=2),
     *             @OA\Property(property="is_favori", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
     * )
     */
    public function checkFavori($paroisse_id): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Utilisateur non authentifié.'], 401);
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
     *         @OA\JsonContent(
     *             required={"paroisse_id"},
     *             @OA\Property(property="paroisse_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Action sur le favori effectuée",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Paroisse ajoutée aux favoris"),
     *             @OA\Property(property="data", ref="#/components/schemas/Favori")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
     * )
     */
    public function toggleFavori(Request $request): JsonResponse
    {
        $validated = $request->validate(['paroisse_id' => 'required|exists:paroisses,id']);
        $user = Auth::user();

        $favori = Favori::where('user_id', $user->id)
            ->where('paroisse_id', $validated['paroisse_id'])
            ->first();

        if ($favori) {
            $favori->delete();
            return response()->json(['status' => 'success', 'message' => 'Paroisse retirée des favoris']);
        } else {
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
}
