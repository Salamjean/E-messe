<?php

namespace App\Http\Controllers\Api\Paiement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Messe;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaiementSuccessNotification;
use App\Notifications\PaiementEchecNotification;

class PaiementController extends Controller
{
public function initierPaiement(Request $request)
{
    $request->validate([
        'messe_id' => 'required|exists:messes,id',
        'montant'  => 'required|numeric|min:100',
    ]);

    $user = $request->user();
    $messe = Messe::findOrFail($request->messe_id);

    // Vérification des données utilisateur essentielles
    if (!$user->email) {
        return response()->json([
            'message' => 'Email utilisateur manquant'
        ], 400);
    }

    // Référence unique
    $reference = 'MESSE_CINET_' . time() . '_' . $user->id;

    try {
        // Création locale du paiement
        $paiement = Paiement::create([
            'messe_id' => $messe->id,
            'user_id'  => $user->id,
            'reference'=> $reference,
            'montant'  => $request->montant,
            'devise'   => 'XOF',
            'methode'  => 'cinetpay',
            'statut'   => 'en_attente',
        ]);

        // URLs de callback
        $returnUrl = route('cinetpay.success', ['transaction_id' => $reference]);
        $cancelUrl = route('cinetpay.cancel', ['transaction_id' => $reference]);
        $notifyUrl = route('cinetpay.webhook');

        // Préparation des données pour CinetPay
        $payload = [
            'apikey'          => env('CINETPAY_API_KEY'),
            'site_id'         => env('CINETPAY_SITE_ID'),
            'transaction_id'  => $reference,
            'amount'          => (int)$request->montant,
            'currency'        => 'XOF',
            'description'     => 'Offrande de Messe - ' . $messe->titre,
            'return_url'      => $returnUrl,
            'cancel_url'      => $cancelUrl,
            'notify_url'      => $notifyUrl,
            'customer_name'   => $user->name ?: 'Fidele',
            'customer_surname'=> $user->name ?: 'Fidele',
            'customer_email'  => $user->email,
            'customer_phone_number' => $user->phone ?? '00000000',
            'channels'        => 'ALL',
            'metadata'        => json_encode([
                'user_id' => $user->id,
                'messe_id' => $messe->id,
                'paiement_id' => $paiement->id
            ])
        ];

        Log::info("Envoi à CinetPay", $payload);

        // Appel CinetPay
        $response = Http::timeout(30)
            ->withOptions(['verify' => false])
            ->post('https://api-checkout.cinetpay.com/v2/payment', $payload);

        $data = $response->json();

        // Journalisation de la réponse complète
        Log::info("Réponse CinetPay", ['response' => $data]);

        // Gestion d'erreur améliorée
        if (!$response->successful()) {
            Log::error("Erreur HTTP CinetPay", [
                'status' => $response->status(),
                'response' => $data
            ]);
            
            $paiement->update([
                'statut' => 'echec', 
                'donnees_transaction' => $data
            ]);

            return response()->json([
                'message' => 'Erreur de connexion avec le service de paiement',
                'details' => $data['message'] ?? 'Erreur inconnue'
            ], 400);
        }

        if (!isset($data['data']['payment_url'])) {
            Log::error("Payment URL manquante", ['response' => $data]);
            
            $paiement->update([
                'statut' => 'echec', 
                'donnees_transaction' => $data
            ]);

            $errorMessage = $data['message'] ?? 
                           $data['description'] ?? 
                           'Erreur lors de la création du paiement';

            return response()->json([
                'message' => "Erreur CinetPay: " . $errorMessage,
                'code' => $data['code'] ?? 'UNKNOWN_ERROR'
            ], 400);
        }

        // Succès - mise à jour du paiement
        $paiement->update([
            'donnees_transaction' => $data,
            'url_paiement' => $data['data']['payment_url']
        ]);

        return response()->json([
            'statut'       => 'success',
            'reference'    => $reference,
            'checkout_url' => $data['data']['payment_url'],
            'message'      => 'Paiement initié avec succès'
        ]);

    } catch (\Exception $e) {
        Log::error("Exception CinetPay: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'message' => 'Erreur serveur lors de l\'initialisation du paiement',
            'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne'
        ], 500);
    }
}

    /**
     * Webhook appelé par CinetPay (Server-to-Server)
     */
    public function handleWebhook(Request $request)
    {
        // CinetPay envoie souvent les données en x-www-form-urlencoded
        $transactionId = $request->input('cpm_trans_id') ?? $request->input('transaction_id');

        if (!$transactionId) {
            Log::warning("Webhook CinetPay: ID manquant", $request->all());
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::with('user', 'messe')->where('reference', $transactionId)->first();

        if (!$paiement) {
            return response()->json(['message' => 'Transaction inconnue'], 404);
        }

        // Vérifier l'état réel via l'API CinetPay (plus sûr que de faire confiance aux POST params)
        $statusData = $this->verifyCinetPayStatus($transactionId, $paiement->site_id ?? env('CINETPAY_SITE_ID'));

        if ($statusData['status'] === 'ACCEPTED') {
            if ($paiement->statut !== 'paye') {
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                    'donnees_transaction' => array_merge($paiement->donnees_transaction ?? [], $statusData),
                ]);

                if ($paiement->messe) {
                    $paiement->messe->update(['statut' => 'en attente']);
                }

                if ($paiement->user) {
                    $paiement->user->notify(new PaiementSuccessNotification($paiement));
                }
            }
            return response()->json(['message' => 'Paiement validé']);

        } elseif ($statusData['status'] === 'REFUSED') {
            $paiement->update(['statut' => 'echec', 'donnees_transaction' => $statusData]);
            
            if ($paiement->user) {
                $paiement->user->notify(new PaiementEchecNotification($paiement));
            }
            return response()->json(['message' => 'Paiement échoué']);
        }

        return response()->json(['message' => 'Statut en attente ou inconnu']);
    }

    /**
     * Helper pour vérifier le statut via l'API CinetPay
     */
    public static function verifyCinetPayStatus($transactionId, $siteId)
    {
        try {
            $response = Http::post('https://api-checkout.cinetpay.com/v2/payment/check', [
                'apikey'         => env('CINETPAY_API_KEY'),
                'site_id'        => $siteId,
                'transaction_id' => $transactionId,
            ]);
            
            $data = $response->json();
            return [
                'status' => $data['data']['status'] ?? 'UNKNOWN',
                'details' => $data
            ];
        } catch (\Exception $e) {
            Log::error("Erreur Check CinetPay: " . $e->getMessage());
            return ['status' => 'ERROR', 'details' => $e->getMessage()];
        }
    }
}