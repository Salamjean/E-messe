<?php

namespace App\Http\Controllers\Api\Messe;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Models\Paiement;
use App\Models\Favoris;
use App\Models\User;    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Notifications\NouveauEvenementParoisseNotification;
use App\Notifications\MesseEnAttentePaiementNotification;
use Illuminate\Support\Facades\Notification;

class MesseController extends Controller
{
    /**
     * Liste des messes actives de l'utilisateur connecté.
     */

        /**
     * @OA\Get(
     *     path="/api/messes",
     *     summary="Liste des messes actives de l'utilisateur connecté",
     *     tags={"Messes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des messes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="messes",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Messe")
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request): JsonResponse
    {

        $messes = $request->user()->messes()
            ->with(['paroisse', 'paiements'])
            ->orderByDesc('created_at')
            ->get();

        // Ajouter le nom de la paroisse à chaque messe
        $messes->transform(function ($messe) {
            $messe->paroisse_name = $messe->paroisse ? $messe->paroisse->name : null;
            unset($messe->paroisse);
            return $messe;
        });
        return response()->json([
            'status' => 'success',
            'messes' => $messes
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/messes/specifiques",
     *     summary="Liste des messes selon un type spécifique",
     *     tags={"Messes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="celebration_choisie",
     *         in="query",
     *         required=true,
     *         description="Type de messe à filtrer",
     *         @OA\Schema(type="string", enum={"Messe quotidienne","Messe dominicale","Messe solennelle"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste filtrée des messes",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="celebration_choisie", type="string"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Messe")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Paramètre manquant"),
     *     @OA\Response(response=422, description="Type de célébration invalide")
     * )
     */
    public function demandesSpecifiques(Request $request): JsonResponse
    {
        $user = $request->user();
        $celebration = $request->query('celebration_choisie'); // ex: "Messe dominicale"

        // Validation du paramètre
        if (!$celebration) {
            return response()->json([
                'status' => 'error',
                'message' => 'Le paramètre celebration_choisie est requis.'
            ], 400);
        }

        // Vérifie si le type de célébration est valide
        $validCelebrations = ['Messe quotidienne', 'Messe dominicale', 'Messe solennelle'];
        if (!in_array($celebration, $validCelebrations)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Type de célébration invalide.'
            ], 422);
        }

        // Récupération des messes correspondant au type choisi
        $messes = $user->messes()
            ->where('celebration_choisie', $celebration)
            ->orderByDesc('created_at')
            ->get();

        // Réponse structurée
        return response()->json([
            'status' => 'success',
            'celebration_choisie' => $celebration,
            'total' => $messes->count(),
            'data' => $messes
        ]);
    }

    /**
     * Historique des messes (annulées ou célébrées).
     */

        /**
     * @OA\Get(
     *     path="/api/messes/history",
     *     summary="Historique des messes (annulées ou célébrées)",
     *     tags={"Messes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Historique des messes",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="history",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Messe")
     *             )
     *         )
     *     )
     * )
     */
public function history(Request $request): JsonResponse
{
    // Récupérer les messes annulées ou célébrées de l'utilisateur connecté
    $messes = $request->user()->messes()
        ->with(['paroisse', 'paiements'])
        ->whereIn('statut', ['annulee', 'celebre'])
        ->orderByDesc('created_at')
        ->get();

    // Ajouter le nom de la paroisse à chaque messe
    $messes->transform(function ($messe) {
        $messe->paroisse_name = $messe->paroisse ? $messe->paroisse->name : null;
        unset($messe->paroisse); // On enlève l'objet paroisse complet si tu veux un JSON plus léger
        return $messe;
    });

    return response()->json([
        'status' => 'success',
        'history' => $messes,
    ]);
}

public function en_cours(Request $request): JsonResponse
{
    $messes = $request->user()->messes()
        ->with(['paroisse', 'paiements'])
        ->whereIn('statut', ['en_attente_paiement', 'en attente', 'confirmee'])
        ->orderByDesc('created_at')
        ->get();

    // Ajouter le nom de la paroisse à chaque messe
    $messes->transform(function ($messe) {
        $messe->paroisse_name = $messe->paroisse ? $messe->paroisse->name : null;
        unset($messe->paroisse);
        return $messe;
    });

    return response()->json([
        'status' => 'success',
        'messes' => $messes,
    ]);
}



    /**
     * Créer une nouvelle demande de messe.
     */

        /**
     * @OA\Post(
     *     path="/api/messes",
     *     summary="Créer une nouvelle demande de messe",
     *     tags={"Messes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"motif_intention","celebration_choisie","montant_offrande","date_souhaitee","paroisse_id"},
     *             @OA\Property(property="motif_intention", type="string"),
     *             @OA\Property(property="interception_par", type="string"),
     *             @OA\Property(property="celebration_choisie", type="string", enum={"Messe quotidienne","Messe dominicale","Messe solennelle"}),
     *             @OA\Property(property="jours_quotidienne", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="jours_dominicale", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="montant_offrande", type="number", format="float"),
     *             @OA\Property(property="date_souhaitee", type="string", format="date"),
     *             @OA\Property(property="heure_souhaitee", type="string", format="time"),
     *             @OA\Property(property="paroisse_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Messe créée avec succès", @OA\JsonContent(
     *         @OA\Property(property="status", type="string"),
     *         @OA\Property(property="message", type="string"),
     *         @OA\Property(property="messe", ref="#/components/schemas/Messe"),
     *         @OA\Property(property="paiement", ref="#/components/schemas/Paiement")
     *     )),
     *     @OA\Response(response=422, description="Erreurs de validation"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    /**
     * Créer une nouvelle demande de messe + notifier les abonnés à la paroisse
     */
    public function store(Request $request): JsonResponse
    {
        // 🔹 Validation
        $validator = Validator::make($request->all(), [
            'motif_intention'     => 'required|string|max:255',
            'interception_par'    => 'nullable|string|max:255',
            'celebration_choisie' => 'required|in:Messe quotidienne,Messe dominicale,Messe solennelle',
            'jours_quotidienne'   => 'required_if:celebration_choisie,Messe quotidienne|array',
            'jours_dominicale'    => 'required_if:celebration_choisie,Messe dominicale|array',
            'montant_offrande'    => 'required|numeric|min:0',
            'date_souhaitee'      => 'required|date|after:today',
            'heure_souhaitee'     => 'nullable|date_format:H:i',
            'paroisse_id'         => 'required|exists:paroisses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = $request->user();

            // 🔹 Conversion des jours sélectionnés
            $datesSelectionnees = [];
            if ($request->celebration_choisie === 'Messe quotidienne') {
                $jours = $request->jours_quotidienne ?? [];
                $nomsJours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                foreach ($jours as $jour) {
                    $index = intval($jour) - 1;
                    if (isset($nomsJours[$index])) {
                        $datesSelectionnees[] = $nomsJours[$index];
                    }
                }
            } elseif ($request->celebration_choisie === 'Messe dominicale') {
                $datesSelectionnees = $request->jours_dominicale ?? [];
            }

            $datesJson = !empty($datesSelectionnees) ? json_encode($datesSelectionnees) : null;

            // 🔹 Création de la messe
            $messe = Messe::create([
                'user_id'              => $user->id,
                'paroisse_id'          => $request->paroisse_id,
                'interception_par'     => $request->interception_par,
                'motif_intention'      => $request->motif_intention,
                'date_souhaitee'       => $request->date_souhaitee,
                'heure_souhaitee'      => $request->heure_souhaitee,
                'celebration_choisie'  => $request->celebration_choisie,
                'nom_demandeur'        => $user->name,
                'email_demandeur'      => $user->email,
                'telephone_demandeur'  => $user->contact,
                'statut'               => 'en_attente_paiement',
                'montant_offrande'     => $request->montant_offrande,
                'dates_selectionnees'  => $datesJson,
            ]);

            // --- Envoi de la notification ---

            if ($messe->user) {
                try {
                    // Notification en base
                    $messe->user->notify(new MesseEnAttentePaiementNotification($messe));

                    // Envoi FCM via HTTP
                    $notification = new MesseEnAttentePaiementNotification($messe);
                    $notification->toFcmHttp($messe->user);

                } catch (\Exception $e) {
                    Log::error('Échec de l\'envoi de la notificationen attente de paiement pour la messe #' . $messe->id . ': ' . $e->getMessage());
                }
            }

            $reference = 'MESSE_API_' . time() . '_' . $messe->id;

            // 🔹 Simulation Paiement
            $paiementEffectue = rand(0, 1); // à remplacer par ton intégration réelle
            $paiement = Paiement::create([
                'messe_id'            => $messe->id,
                'user_id'             => $user->id,
                'reference'           => $reference,
                'montant'             => $request->montant_offrande * 1.02,
                'devise'              => 'XOF',
                'methode'             => 'wave',
                'statut'              => $paiementEffectue ? 'paye' : 'en_attente',
                'transaction_id'      => $paiementEffectue ? uniqid('TX_') : null,
                'donnees_transaction' => $paiementEffectue ? json_encode(['message' => 'Paiement réussi']) : null,
                'date_paiement'       => $paiementEffectue ? now() : null,
            ]);

            $messe->update([
                'statut' => $paiementEffectue ? 'en_attente_paiement' : 'en_attente_paiement'
            ]);

            // ===================================================
            // 🔔 ENVOI DE NOTIFICATION FIREBASE AUX FAVORIS
            // ===================================================

            // Charger la paroisse liée
            $messe->load('paroisse');

            // Trouver les utilisateurs qui ont cette paroisse en favori (sauf le créateur)
            $utilisateursANotifier = User::whereHas('favoris', function ($query) use ($request) {
                $query->where('paroisse_id', $request->paroisse_id);
            })->where('id', '!=', $user->id)->get();

            // Si des utilisateurs doivent être notifiés
            if ($utilisateursANotifier->isNotEmpty()) {
                foreach ($utilisateursANotifier as $u) {
                    if ($u->fcm_token) { // Vérifie que l'utilisateur a bien un token Firebase
                        $this->sendFirebaseNotification(
                            $u->fcm_token,
                            'Nouvelle messe dans votre paroisse',
                            "Une nouvelle messe a été demandée à la paroisse {$messe->paroisse->name}."
                        );
                    }
                }
            }

            // ===================================================

            return response()->json([
                'status'   => 'success',
                'message'  => $paiementEffectue
                    ? 'Messe enregistrée et paiement effectué avec succès.'
                    : 'Messe enregistrée, en attente de paiement.',
                'messe'    => $messe,
                'paiement' => $paiement ?? null,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur création messe API', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Une erreur s\'est produite lors de l\'enregistrement.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔥 Fonction d'envoi de notification Firebase (FCM)
     */
    private function sendFirebaseNotification(string $token, string $title, string $body): void
    {
        $serverKey = config('services.firebase.server_key'); // clé FCM à mettre dans .env
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body'  => $body,
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ]);

        Log::info('Notification Firebase envoyée', [
            'token' => $token,
            'response' => $response->json(),
        ]);
    }


    /**
     * Affiche les détails d’une messe spécifique.
     */

        /**
     * @OA\Get(
     *     path="/api/messes/{messeId}",
     *     summary="Afficher les détails d’une messe spécifique",
     *     tags={"Messes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="messeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Détails de la messe", @OA\JsonContent(ref="#/components/schemas/Messe")),
     *     @OA\Response(response=403, description="Accès non autorisé")
     * )
     */

public function show(Request $request, $id): JsonResponse
{
    $messe = Messe::with(['paroisse', 'paiements'])->find($id);

    if (!$messe) {
        return response()->json(['message' => 'Messe introuvable.'], 404);
    }

    if ($request->user()->id !== $messe->user_id) {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    // Ajout du nom de la paroisse dans la réponse
    $messe->paroisse_name = $messe->paroisse ? $messe->paroisse->name : null;

    return response()->json($messe);
}



    /**
     * Met à jour une demande de messe (seulement si "en attente").
     */

        /**
     * @OA\Put(
     *     path="/api/messes/{messeId}",
     *     summary="Mettre à jour une demande de messe",
     *     tags={"Messes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="messeId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="motif_intention", type="string"),
     *         @OA\Property(property="date_souhaitee", type="string", format="date")
     *     )),
     *     @OA\Response(response=200, description="Mise à jour réussie"),
     *     @OA\Response(response=403, description="Accès non autorisé"),
     *     @OA\Response(response=409, description="Cette demande ne peut plus être modifiée"),
     *     @OA\Response(response=422, description="Erreurs de validation")
     * )
     */

    public function update(Request $request, Messe $messe): JsonResponse
    {
        if ($request->user()->id !== $messe->user_id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        if (!in_array($messe->statut, ['en attente', 'en_attente_paiement'])) {
            return response()->json(['message' => 'Cette demande ne peut plus être modifiée.'], 409);
        }

        $validator = Validator::make($request->all(), [
            'motif_intention' => 'sometimes|required|string|max:255',
            'date_souhaitee' => 'sometimes|required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $messe->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Demande de messe mise à jour avec succès.',
            'messe' => $messe
        ]);
    }

    /**
     * Supprime une messe (si non célébrée).
     */

        /**
     * @OA\Delete(
     *     path="/api/messes/{messeId}",
     *     summary="Supprime une messe si non célébrée",
     *     tags={"Messes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="messeId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Messe supprimée"),
     *     @OA\Response(response=403, description="Accès non autorisé"),
     *     @OA\Response(response=409, description="Seules les messes en attente peuvent être supprimées")
     * )
     */
    public function destroy(Request $request, Messe $messe): JsonResponse
    {
        if ($request->user()->id !== $messe->user_id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        if (!in_array($messe->statut, ['en attente', 'en_attente_paiement'])) {
            return response()->json(['message' => 'Seules les messes en attente peuvent être supprimées.'], 409);
        }

        $messe->delete();

        return response()->json(['message' => 'Demande supprimée avec succès.'], 200);
    }
}
