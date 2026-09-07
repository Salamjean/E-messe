<?php

namespace App\Http\Controllers\Api\Paiement;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Messe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
// use App\Services\FirebaseNotificationService;
use App\Notifications\PaiementSuccessNotification;
use App\Notifications\PaiementEchecNotification;
use App\Services\WaveService;

class WaveController extends Controller
{
    protected $waveService;

    public function __construct(WaveService $waveService)
    {
        $this->waveService = $waveService;
    }

    /**
     * Obtenir le checkout_url Wave pour initier un paiement
     */
    public function checkoutUrl(Request $request): JsonResponse
    {
        $request->validate([
            'messe_id' => 'required|exists:messes,id', 
            'montant'  => 'required|numeric|min:100',
        ]);

        $user = $request->user();

        try {
            // 🔹 Récupérer la messe 
            $messe = Messe::findOrFail($request->messe_id);

            // 🔹 Générer une référence unique 
            $reference = 'MESSE_WAVE_' . time() . '_' . $user->id;

            // 🔹 Créer le paiement localement 
            $paiement = Paiement::create([
                'messe_id'  => $messe->id,
                'user_id'   => $user->id,
                'reference' => $reference,
                'montant'   => $request->montant,
                'devise'    => 'XOF',
                'methode'   => 'wave',
                'statut'    => 'en_attente',
            ]);

            // 🔹 URLs de redirection
            $successUrl = route('wave.success', ['ref' => $reference]);
            $errorUrl   = route('wave.error', ['ref' => $reference]);

            // 🔹 Session Wave via WaveService
            $result = $this->waveService->createCheckoutSession(
                $request->montant,
                'XOF',
                $reference,
                $successUrl,
                $errorUrl,
                [
                    'messe_id'  => $messe->id,
                    'user_id'   => $user->id,
                    'reference' => $reference,
                ]
            );

            if (!$result['success'] || empty($result['wave_launch_url'])) {
                Log::error('Erreur Wave API checkoutUrl: ' . json_encode($result));

                $paiement->update(['statut' => 'en_attente']); 
                $messe->update(['statut' => 'en_attente_paiement']); 

                return response()->json([
                    'message' => 'Erreur lors de la création de la session de paiement.',
                    'details' => $result['message'] ?? 'Erreur inconnue.',
                ], 400);
            }

            // 🔹 Mise à jour du paiement avec les données de la transaction
            $paiement->update([
                'transaction_id'      => $result['session_id'] ?? null,
                'donnees_transaction' => $result['data'] ?? [],
            ]);

            return response()->json([
                'reference'       => $reference,
                'checkout_url'    => $result['wave_launch_url'],
                'wave_launch_url' => $result['wave_launch_url'],
                'session_id'      => $result['session_id'] ?? null,
                'statut'          => 'success',
                'status'          => 'success',
                'message'         => 'URL de paiement générée avec succès.',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // 🔴 Messe introuvable
            return response()->json([
                'message' => 'Messe introuvable.',
            ], 404);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // 🔴 Erreur de connexion HTTP
            Log::error('Erreur HTTP Wave: ' . $e->getMessage());

            $paiement->update(['statut' => 'en_attente']);
            $messe->update(['statut' => 'en_attente_paiement']);

            return response()->json([
                'message' => 'Erreur de connexion avec l’API Wave.',
                'error'   => $e->getMessage(),
            ], 502);

        } catch (\Exception $e) {
            // 🔴 Erreur générale 
            Log::error('Erreur inattendue Wave: ' . $e->getMessage());

            // Mettre les statuts en attente si possible
            if (isset($paiement)) {
                $paiement->update(['statut' => 'en_attente']);
            }
            if (isset($messe)) {
                $messe->update(['statut' => 'en_attente_paiement']);
            }

            return response()->json([
                'message' => 'Une erreur inattendue est survenue.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function initier(Request $request): JsonResponse
    {
        $request->validate([
            'messe_id' => 'required|exists:messes,id',
            'montant' => 'required|numeric|min:100',
            'telephone' => 'nullable|string',
        ]);

        $user = $request->user();
        $messe = Messe::findOrFail($request->messe_id);

        $reference = 'MESSE_WAVE_' . time() . '_' . $user->id;

        try {
            // 1️⃣ Crée le paiement localement
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id' => $user->id,
                'reference' => $reference,
                'montant' => $request->montant,
                'devise' => 'XOF',
                'methode' => 'wave',
                'statut' => 'en_attente',
            ]);

            // 2️⃣ Préparer les URLs
            $successUrl = route('wave.success', ['ref' => $reference]);
            $errorUrl   = route('wave.error', ['ref' => $reference]);

            // 3️⃣ Appel via WaveService
            $result = $this->waveService->createCheckoutSession(
                $request->montant,
                'XOF',
                $reference,
                $successUrl,
                $errorUrl,
                [
                    'messe_id' => $messe->id,
                    'user_id' => $user->id,
                    'reference' => $reference,
                ]
            );

            if (!$result['success'] || empty($result['wave_launch_url'])) {
                Log::error('Erreur Wave API initier: ' . json_encode($result));
                return response()->json([
                    'message' => 'Erreur lors de l’initiation du paiement Wave',
                    'details' => $result['message'] ?? 'Erreur inconnue',
                ], 400);
            }

            // 4️⃣ Sauvegarde des infos Wave en base
            $paiement->update([
                'transaction_id' => $result['session_id'] ?? null,
                'donnees_transaction' => $result['data'] ?? [],
            ]);

            // 5️⃣ Retour JSON au front-end
            return response()->json([
                'message' => 'Paiement initié avec succès',
                'paiement' => $paiement,
                'wave' => [
                    'checkout_url' => $result['wave_launch_url'],
                    'wave_launch_url' => $result['wave_launch_url'],
                    'session_id'   => $result['session_id'] ?? null,
                    'success_url'  => $successUrl,
                    'error_url'    => $errorUrl,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur Wave : ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de l’initiation du paiement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Webhook de Wave — confirmation du paiement
     */
    // public function webhook(Request $request): JsonResponse
    // {
    //     Log::info('Webhook Wave reçu : ', $request->all());

    //     $transactionId = $request->input('id');
    //     $status = $request->input('status');

    //     $paiement = Paiement::where('transaction_id', $transactionId)->first();

    //     if ($paiement) {
    //         if ($status === 'successful') {
    //             $paiement->update([
    //                 'statut' => 'paye',
    //                 'date_paiement' => Carbon::now(),
    //                 'donnees_transaction' => $request->all(),
    //             ]);

    //             // Met à jour la messe si besoin
    //             if ($paiement->messe) {
    //                 $paiement->messe->update(['statut' => 'paye']);
    //             }

    //             Log::info("Paiement Wave réussi pour la messe #{$paiement->messe_id}");
    //         } elseif ($status === 'failed') {
    //             $paiement->update([
    //                 'statut' => 'echec',
    //                 'donnees_transaction' => $request->all(),
    //             ]);
    //             Log::warning("Paiement Wave échoué pour la messe #{$paiement->messe_id}");
    //         }
    //     } else {
    //         Log::warning('Webhook reçu pour transaction inconnue : ' . $transactionId);
    //     }

    //     return response()->json(['message' => 'Webhook traité', 'status' => $status]);
    // }


    public function webhook(Request $request): JsonResponse
    {
        Log::info('Webhook Wave reçu : ', $request->all());

        // La structure des webhooks Wave enveloppe souvent les données dans 'data'
        $data = $request->input('data') ?? $request->all(); 
        $transactionId = $data['id'] ?? $request->input('id');
        $clientReference = $data['client_reference'] ?? $request->input('client_reference');
        $status = $data['payment_status'] ?? $data['status'] ?? $request->input('status');
        $checkoutStatus = $data['checkout_status'] ?? null;
        $eventType = $request->input('type');

        if (!$transactionId && !$clientReference) {
            Log::warning('Webhook Wave reçu sans ID de transaction ni client_reference.');
            return response()->json(['message' => 'Identifiant de transaction manquant'], 400);
        }

        // On cherche le paiement par son transaction_id (ou par sa référence de messe)
        $paiement = Paiement::with('user', 'messe')
            ->where(function ($query) use ($transactionId, $clientReference) {
                if ($transactionId) {
                    $query->where('transaction_id', $transactionId);
                }
                if ($clientReference) {
                    $query->orWhere('reference', $clientReference);
                }
            })
            ->first();

        if (!$paiement) {
            Log::warning("⚠️ Webhook reçu pour transaction inconnue : {$transactionId} / {$clientReference}");
            return response()->json(['message' => 'Transaction non reconnue']);
        }
        
        // Évite de traiter plusieurs fois le même webhook
        if (in_array($paiement->statut, ['paye', 'echec'])) {
            Log::info("ℹ️ Webhook pour paiement #{$paiement->id} déjà traité. Statut: {$paiement->statut}.");
            return response()->json(['message' => 'Webhook déjà traité']);
        }

        $isSuccess = in_array($status, ['successful', 'succeeded', 'complete', 'paid']) 
            || $checkoutStatus === 'complete' 
            || $eventType === 'checkout.session.completed';

        $isFailed = in_array($status, ['failed', 'cancelled', 'expired']) 
            || $eventType === 'checkout.session.failed';

        // --- CAS 1 : PAIEMENT RÉUSSI ---
        if ($isSuccess) {
            $paiement->update([
                'statut' => 'paye',
                'methode' => 'wave',
                'date_paiement' => Carbon::now(),
                'donnees_transaction' => $request->all(),
            ]);

            if ($paiement->messe) {
                $paiement->messe->update(['statut' => 'en attente']);
            }

            // 🔔 ENVOI DE LA NOTIFICATION DE SUCCÈS
            if ($paiement->user) {
                $paiement->user->notify(new PaiementSuccessNotification($paiement));
            }

            Log::info("✅ Paiement Wave réussi pour la messe #{$paiement->messe_id}");

        // --- CAS 2 : PAIEMENT ÉCHOUÉ ---
        } elseif ($isFailed) {
            $paiement->update([
                'statut' => 'echec',
                'methode' => 'wave',
                'donnees_transaction' => $request->all(),
            ]);

            if ($paiement->messe) {
                $paiement->messe->update(['statut' => 'en_attente_paiement']);
            }

            // 🔔 ENVOI DE LA NOTIFICATION D'ÉCHEC
            if ($paiement->user) {
                $paiement->user->notify(new PaiementEchecNotification($paiement));
            }
            
            Log::warning("❌ Paiement Wave échoué pour la messe #{$paiement->messe_id}");
        }

        return response()->json(['message' => 'Webhook traité']);
    }
    
    /**
     * Vérifier le statut d’une session de paiement sur Wave
     */
    public function verifier($id): JsonResponse
    {
        try {
            $data = $this->waveService->verifyBySessionId($id);

            if ($data) {
                return response()->json($data, 200);
            }

            return response()->json([
                'message' => 'Session de paiement introuvable ou erreur de communication',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la vérification du paiement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function success(Request $request)
    {
        return app(\App\Http\Controllers\Redirectionpaiement\RedirectController::class)->success($request);
    }

    public function error(Request $request)
    {
        return app(\App\Http\Controllers\Redirectionpaiement\RedirectController::class)->error($request);
    }


    /**
     * Réception du webhook Wave pour le paiement d'une messe.
     *
     * @param Request $request
     * @return JsonResponse
     */
    // public function webhook(Request $request): JsonResponse
    // {
    //     Log::info('📩 Webhook Wave reçu', $request->all());

    //     // ✅ Extraction des informations principales
    //     $transactionId = $request->input('id');
    //     $status        = $request->input('status');

    //     // ✅ Récupération du paiement associé à la transaction, avec l'utilisateur et la messe
    //     $paiement = Paiement::with(['user', 'messe'])->where('transaction_id', $transactionId)->first();

    //     if (!$paiement) {
    //         Log::warning("⚠️ Webhook reçu pour transaction inconnue : {$transactionId}");
    //         return response()->json([
    //             'message' => 'Transaction non reconnue',
    //             'status'  => 'ignored',
    //         ]);
    //     }

    //     try {
    //         // ============================
    //         // 🔹 CAS 1 : Paiement réussi
    //         // ============================
    //         if ($status === 'successful') {
    //             $paiement->update([
    //                 'statut'              => 'paye',
    //                 'date_paiement'       => Carbon::now(),
    //                 'donnees_transaction' => json_encode($request->all()),
    //             ]);

    //             if ($paiement->messe) {
    //                 $paiement->messe->update(['statut' => 'paye']);
    //             }

    //             // 🔔 Notification utilisateur
    //             if ($paiement->user && $paiement->user->fcm_token) {
    //                 $this->sendFirebaseNotification(
    //                     $paiement->user->fcm_token,
    //                     'Paiement réussi ✅',
    //                     "Votre paiement pour la messe #{$paiement->messe_id} a été confirmé."
    //                 );
    //             }

    //             // (Optionnel : notification Laravel classique)
    //             $paiement->user?->notify(new PaiementSuccessNotification($paiement));

    //             Log::info("✅ Paiement Wave réussi pour la messe #{$paiement->messe_id}");
    //         }

    //         // ============================
    //         // 🔹 CAS 2 : Paiement échoué
    //         // ============================
    //         elseif ($status === 'failed') {
    //             $paiement->update([
    //                 'statut'              => 'echec',
    //                 'donnees_transaction' => json_encode($request->all()),
    //             ]);

    //             // 🔔 Notification utilisateur
    //             if ($paiement->user && $paiement->user->fcm_token) {
    //                 $this->sendFirebaseNotification(
    //                     $paiement->user->fcm_token,
    //                     'Échec du paiement ❌',
    //                     "Votre paiement pour la messe #{$paiement->messe_id} a échoué. Veuillez réessayer."
    //                 );
    //             }

    //             $paiement->user?->notify(new PaiementEchecNotification($paiement));

    //             Log::warning("❌ Paiement Wave échoué pour la messe #{$paiement->messe_id}");
    //         }

    //         // ============================
    //         // 🔹 Autres statuts possibles
    //         // ============================
    //         else {
    //             Log::info("ℹ️ Statut de paiement non pris en charge : {$status}");
    //         }

    //         // ✅ Réponse standard pour Wave
    //         return response()->json([
    //             'message' => 'Webhook traité avec succès',
    //             'status'  => $status,
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('🔥 Erreur lors du traitement du webhook Wave', [
    //             'message' => $e->getMessage(),
    //             'transaction_id' => $transactionId,
    //         ]);

    //         return response()->json([
    //             'message' => 'Erreur serveur',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // /**
    //  * 🔥 Fonction d'envoi de notification Firebase (FCM)
    //  *
    //  * @param string $token
    //  * @param string $title
    //  * @param string $body
    //  * @return void
    //  */
    // private function sendFirebaseNotification(string $token, string $title, string $body): void
    // {
    //     $serverKey = config('services.firebase.server_key');

    //     $response = Http::withHeaders([
    //         'Authorization' => 'key=' . $serverKey,
    //         'Content-Type'  => 'application/json',
    //     ])->post('https://fcm.googleapis.com/fcm/send', [
    //         'to' => $token,
    //         'notification' => [
    //             'title' => $title,
    //             'body'  => $body,
    //             'sound' => 'default',
    //         ],
    //         'data' => [
    //             'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
    //         ],
    //     ]);

    //     Log::info('🔔 Notification Firebase envoyée', [
    //         'token' => $token,
    //         'response' => $response->json(),
    //     ]);
    // }


}














