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
        'montant'   => 'required|numeric|min:100',
        'telephone' => 'required|string|min:8',
    ]);

    $user = $request->user();
    $messe = Messe::findOrFail($request->messe_id);
    $reference = 'MESSE_WAVE_' . time() . '_' . $user->id;

    // Créer le paiement localement
    $paiement = Paiement::create([
        'messe_id' => $messe->id,
        'user_id'  => $user->id,
        'reference'=> $reference,
        'montant'  => $request->montant,
        'devise'   => 'XOF',
        'methode'  => 'wave',
        'statut'   => 'en_attente',
    ]);

    $baseUrl = 'https://sancta-missa.com';
    $successUrl = $baseUrl . '/paiement/wave/success?ref=' . $reference;
    $errorUrl   = $baseUrl . '/paiement/wave/error?ref=' . $reference;

    try {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('WAVE_API_KEY'),
                'Content-Type'  => 'application/json',
            ])
            ->post('https://api.wave.com/v1/checkout/sessions', [
                'amount'      => (string) $request->montant,
                'currency'    => 'XOF',
                'success_url' => $successUrl,
                'error_url'   => $errorUrl
            ]);

        $data = $response->json();

        if (!$response->successful()) {
            Log::error('Erreur Wave API: ' . json_encode($data));
            return response()->json([
                'message' => 'Erreur lors de la création de la session de paiement',
                'details' => $data['details'] ?? $data['message'] ?? 'Erreur inconnue',
            ], 400);
        }

        // Mettre à jour transaction_id et données
        $paiement->update([
            'transaction_id'      => $data['id'] ?? null,
            'donnees_transaction' => $data,
        ]);

        // Vérifier que le wave_launch_url est présent dans la réponse
        if (!isset($data['wave_launch_url'])) {
            Log::error('Wave launch URL manquant dans la réponse Wave: ' . json_encode($data));
            return response()->json([
                'message' => 'URL de paiement non générée par Wave',
                'details' => $data,
            ], 500);
        }

        return response()->json([
            'reference'    => $reference,
            'checkout_url' => $data['wave_launch_url'], // ⬅️ CORRECTION ICI
            'session_id'   => $data['id'],
            'statut'       => 'success',
            'message'      => 'URL de paiement générée avec succès'
        ], 200);

    } catch (\Exception $e) {
        Log::error('Erreur Wave : ' . $e->getMessage());
        return response()->json([
            'message' => 'Impossible de générer le checkout_url',
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
