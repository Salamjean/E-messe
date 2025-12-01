<?php

namespace App\Http\Controllers\Api\Paroissien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Paroissien;

class ParoissienController extends Controller
{
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

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photos_paroissiens', 'public');
                $data['photo'] = $path;
            }

            // 5. Création
            $paroissien = Paroissien::create($data);

            // 6. Réponse JSON (Code 201 = Created)
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
}
