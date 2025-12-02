<?php

namespace App\Http\Controllers\Api\Paroissien;

use App\Http\Controllers\Controller;
use App\Models\Paroissien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ParoissienController extends Controller
{
    // Fonction pour enregistrer un nouveau paroissien
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'situation_matrimoniale' => 'required|string',
            'adresse' => 'required|string',
            'statut_activite' => 'required|string',
            'nom_paroisse' => 'required|string',
            'telephone' => 'required|string',
            'est_dans_mouvement' => 'nullable|boolean',
            'nom_mouvement' => 'nullable|required_if:est_dans_mouvement,true|string',
            'est_baptise' => 'nullable|boolean',
            'date_bapteme' => 'nullable|required_if:est_baptise,true|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();

            // 1. Récupération de l'ID de l'utilisateur connecté
            // Assure-toi que la route est protégée par un middleware (ex: auth:sanctum)
            if ($request->user()) {
                $data['user_id'] = $request->user()->id;
            }

            // 2. Gestion des booléens
            $estDansMouvement = $request->boolean('est_dans_mouvement');
            $estBaptise = $request->boolean('est_baptise');

            $data['est_dans_mouvement'] = $estDansMouvement;
            $data['est_baptise'] = $estBaptise;

            if (! $estDansMouvement) {
                $data['nom_mouvement'] = null;
            }
            if (! $estBaptise) {
                $data['date_bapteme'] = null;
            }

            // 3. Gestion de la photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photos_paroissiens', 'public');
                $data['photo'] = $path;
            }

            // 4. Création
            $paroissien = Paroissien::create($data);

            // 5. Réponse JSON (Code 201 = Created)
            return response()->json([
                'status' => true,
                'message' => 'Fidèle enregistré avec succès.',
                'data' => $paroissien,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur lors de l\'enregistrement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fonction pour afficher un paroissien
    public function show(Request $request, $id)
    {
        try {
            $user_id = $request->user()->id;
            $paroissien = Paroissien::where('user_id', $user_id)->find($id);

            if (! $paroissien) {
                return response()->json([
                    'status' => false,
                    'message' => 'Paroissien non trouvé',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Détails du paroissien récupérés avec succès',
                'data' => $paroissien,
                // Note : $paroissien contient maintenant 'user_id' grâce au store()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fonction pour mettre à jour un paroissien
    public function update(Request $request, $id)
    {
        // 1. Recherche du paroissien
        $paroissien = Paroissien::find($id);

        if (! $paroissien) {
            return response()->json([
                'status' => false,
                'message' => 'Paroissien non trouvé',
            ], 404);
        }

        // 2. Validation des données
        $validator = Validator::make($request->all(), [
            'nom_prenom' => 'sometimes|required|string|max:255',
            'date_naissance' => 'sometimes|required|date',
            'sexe' => 'sometimes|required|in:M,F',
            'situation_matrimoniale' => 'sometimes|required|string',
            'adresse' => 'sometimes|required|string',
            'statut_activite' => 'sometimes|required|string',
            'nom_paroisse' => 'sometimes|required|string',
            'telephone' => 'sometimes|required|string',
            'est_dans_mouvement' => 'nullable|boolean',
            'nom_mouvement' => 'nullable|required_if:est_dans_mouvement,true|string',
            'est_baptise' => 'nullable|boolean',
            'date_bapteme' => 'nullable|required_if:est_baptise,true|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // 3. Récupération des données validées
            $data = $validator->validated();

            // 4. Gestion des champs conditionnels
            $estDansMouvement = isset($data['est_dans_mouvement'])
                ? filter_var($data['est_dans_mouvement'], FILTER_VALIDATE_BOOLEAN)
                : $paroissien->est_dans_mouvement;

            $estBaptise = isset($data['est_baptise'])
                ? filter_var($data['est_baptise'], FILTER_VALIDATE_BOOLEAN)
                : $paroissien->est_baptise;

            // Si est_dans_mouvement est false, on vide nom_mouvement
            if (! $estDansMouvement) {
                $data['nom_mouvement'] = null;
            }

            // Si est_baptise est false, on vide date_bapteme
            if (! $estBaptise) {
                $data['date_bapteme'] = null;
            }

            // 5. Gestion de la photo
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($paroissien->photo && Storage::disk('public')->exists($paroissien->photo)) {
                    Storage::disk('public')->delete($paroissien->photo);
                }

                $path = $request->file('photo')->store('photos_paroissiens', 'public');
                $data['photo'] = $path;
            }

            // 6. Mise à jour du paroissien
            $paroissien->update($data);

            // 7. Rafraîchir l'objet pour avoir les données mises à jour
            $paroissien->refresh();

            return response()->json([
                'status' => true,
                'message' => 'Paroissien mis à jour avec succès',
                'data' => $paroissien,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur lors de la mise à jour',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fonction pour obtenir les données par défaut (optionnel)
    public function getDefaults($id)
    {
        try {
            $paroissien = Paroissien::find($id);

            if (! $paroissien) {
                return response()->json([
                    'status' => false,
                    'message' => 'Paroissien non trouvé',
                ], 404);
            }

            // Retourne les données avec les valeurs par défaut
            return response()->json([
                'status' => true,
                'data' => [
                    'current_values' => $paroissien,
                    'default_values' => [
                        'sexe_options' => ['M', 'F'],
                        'situation_matrimoniale_options' => ['Célibataire', 'Marié(e)', 'Veuf/Veuve', 'Divorcé(e)'],
                        'statut_activite_options' => ['Actif', 'Retraité', 'Étudiant', 'Sans emploi'],
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
