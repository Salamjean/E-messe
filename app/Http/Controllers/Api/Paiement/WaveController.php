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

class WaveController extends Controller
{
    /**
     * Initier un paiement Wave pour une messe
     */

    /**
     * Obtenir le checkout_url Wave pour initier un paiement
     */
    public function checkoutUrl(Request $request): JsonResponse
    {
        $request->validate([
            // 'messe_id' => 'required|exists:messes,id', 
            'montant'  => 'required|numeric|min:100',
        ]);

        $user = $request->user();

        try {
            // 🔹 Récupérer la messe 
            $messe = Messe::findOrFail($request->messe_id);
            $messe->update(['statut' => 'en attente']);

            // 🔹 Générer une référence unique 
            $reference = 'MESSE_WAVE_' . time() . '_' . $user->id;

            // 🔹 Créer le paiement localement 
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id'  => $user->id,
                'reference'=> $reference,
                'montant'  => $request->montant,
                'devise'   => 'XOF',
                'methode'  => 'wave',
                'statut'   => 'paye',
            ]);

            // 🔹 URLs de redirection
            $baseUrl = 'https://sancta-missa.com';
            $successUrl = $baseUrl . '/paiement/wave/success?ref=' . $reference;
            $errorUrl   = $baseUrl . '/paiement/wave/error?ref=' . $reference;

            // 🔹 Requête vers l’API Wave
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('WAVE_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.wave.com/v1/checkout/sessions', [
                    'amount'      => (string) $request->montant,
                    'currency'    => 'XOF',
                    'success_url' => $successUrl,
                    'error_url'   => $errorUrl,
                ]);

            $data = $response->json();

            // 🔹 Si la requête a échoué
            if (!$response->successful()) {
                Log::error('Erreur Wave API: ' . json_encode($data));

                $paiement->update(['statut' => 'en_attente']); 
                $messe->update(['statut' => 'en_attente_paiement']); 

                return response()->json([
                    'message' => 'Erreur lors de la création de la session de paiement.',
                    'details' => $data['message'] ?? 'Erreur inconnue.',
                ], 400);
            }

            // 🔹 Vérifier que le lien de paiement existe
            if (!isset($data['wave_launch_url'])) {
                Log::error('Wave launch URL manquant: ' . json_encode($data));

                $paiement->update(['statut' => 'en_attente']);
                $messe->update(['statut' => 'en_attente_paiement']);

                return response()->json([
                    'message' => 'URL de paiement non générée par Wave.',
                    'details' => $data,
                ], 500);
            }

            // 🔹 Mise à jour du paiement avec les données de la transaction
            $paiement->update([
                'transaction_id'      => $data['id'] ?? null,
                'donnees_transaction' => $data,
            ]);

            return response()->json([
                'reference'    => $reference,
                'checkout_url' => $data['wave_launch_url'],
                'session_id'   => $data['id'] ?? null,
                'statut'       => 'success',
                'message'      => 'URL de paiement générée avec succès.',
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
        'telephone' => 'required|string|min:8',
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
        $successUrl = route('wave.success', ['ref' => $reference]); // redirection en cas de succès
        $errorUrl   = route('wave.error', ['ref' => $reference]);   // redirection en cas d'échec

        // 3️⃣ Appel API Wave
        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('WAVE_API_KEY'),
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.wave.com/v1/checkout/sessions', [
                'amount' => (string) $request->montant, // Wave exige une string
                'currency' => 'XOF',
                'success_url' => $successUrl,
                'error_url' => $errorUrl,
                'metadata' => [
                    'messe_id' => $messe->id,
                    'user_id' => $user->id,
                    'reference' => $reference,
                ],
            ]);

        $data = $response->json();

        // 4️⃣ Sauvegarde des infos Wave en base
        $paiement->update([
            'transaction_id' => $data['id'] ?? null,
            'donnees_transaction' => $data,
        ]);

        // 5️⃣ Retour JSON au front-end
        return response()->json([
            'message' => 'Paiement initié avec succès',
            'paiement' => $paiement,
            'wave' => [
                'checkout_url' => $data['checkout_url'] ?? null,
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
    public function webhook(Request $request): JsonResponse
    {
        Log::info('Webhook Wave reçu : ', $request->all());

        $transactionId = $request->input('id');
        $status = $request->input('status');

        $paiement = Paiement::where('transaction_id', $transactionId)->first();

        if ($paiement) {
            if ($status === 'successful') {
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => Carbon::now(),
                    'donnees_transaction' => $request->all(),
                ]);

                // Met à jour la messe si besoin
                if ($paiement->messe) {
                    $paiement->messe->update(['statut' => 'paye']);
                }

                Log::info("Paiement Wave réussi pour la messe #{$paiement->messe_id}");
            } elseif ($status === 'failed') {
                $paiement->update([
                    'statut' => 'echec',
                    'donnees_transaction' => $request->all(),
                ]);
                Log::warning("Paiement Wave échoué pour la messe #{$paiement->messe_id}");
            }
        } else {
            Log::warning('Webhook reçu pour transaction inconnue : ' . $transactionId);
        }

        return response()->json(['message' => 'Webhook traité', 'status' => $status]);
    }

    /**
     * Vérifier le statut d’une transaction sur Wave
     */
    public function verifier($id): JsonResponse
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('WAVE_API_KEY'),
            ])->get("https://api.wave.com/v1/checkout/sessions/{$id}");

            return response()->json($response->json(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la vérification du paiement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function success(Request $request)
{
    $ref = $request->query('ref');
    return response()->json(['message' => "Paiement réussi pour la référence {$ref}"]);
}

public function error(Request $request)
{
    $ref = $request->query('ref');
    return response()->json(['message' => "Paiement échoué pour la référence {$ref}"], 400);
}
}
