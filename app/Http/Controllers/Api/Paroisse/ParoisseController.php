<?php

namespace App\Http\Controllers\Api\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use App\Models\Favori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ParoisseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/paroisses",
     *     summary="Lister les paroisses avec filtres et pagination",
     *     description="Récupère une liste paginée des paroisses avec filtres optionnels et favoris utilisateur.",
     *     tags={"Paroisses"},
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


        // $montantMoyen =  $paroisse->messes->pluck('montant_offrande');
        $montantMoyen = $paroisse->value('montant_offrande');
            // 🖼️ Image depuis storage
            
                
        $profilePictureUrl = $paroisse->profile_picture
            ? Storage::url('paroisses/' . $paroisse->profile_picture)
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
                'montant_unitaire' => $montantMoyen,
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
     * )
     */
    public function show($id): JsonResponse
    {
        $paroisse = Paroisse::with(['commune.ville', 'messes'])->findOrFail($id);

        $isFavori = Auth::check() && Favori::where('user_id', Auth::id())
            ->where('paroisse_id', $paroisse->id)
            ->exists();

        $montantTotal = $paroisse->messes->sum('montant_offrande');
        $montantMoyen = $paroisse->montant_offrande;


        $profilePictureUrl = $paroisse->profile_picture
            ? Storage::url('paroisses/' . $paroisse->profile_picture)
            : null;

        $data = [
            'id' => $paroisse->id,
            'name' => $paroisse->name,
            'email' => $paroisse->email,
            'contact' => $paroisse->contact,
            'profile_picture' => $profilePictureUrl,
            'commune' => $paroisse->commune->nom_commune ?? null,
            'ville' => $paroisse->commune->ville->nom_ville ?? null,
            'montant_total_messes' => $montantTotal,
            'montant_unitaire' => $montantMoyen,
            'is_favori' => $isFavori,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Détails de la paroisse récupérés avec succès',
            'data' => $data,
        ]);
    }

    /**
     * 🔁 Vérifier le statut favori
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
     * 🔁 Ajouter ou retirer un favori
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
