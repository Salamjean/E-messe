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

            // Log sécurisé (sans données sensibles)
            $this->safeLog("Envoi à CinetPay", [
                'reference' => $reference,
                'montant' => $request->montant,
                'user_id' => $user->id,
                'messe_id' => $messe->id
            ]);

            // Appel CinetPay
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->post('https://api-checkout.cinetpay.com/v2/payment', $payload);

            $data = $response->json();

            // Journalisation sécurisée de la réponse
            $this->safeLog("Réponse CinetPay", [
                'reference' => $reference,
                'successful' => $response->successful(),
                'has_payment_url' => isset($data['data']['payment_url']),
                'status_code' => $response->status()
            ]);

            // Gestion d'erreur améliorée
            if (!$response->successful()) {
                $this->safeLog("Erreur HTTP CinetPay", [
                    'status' => $response->status(),
                    'reference' => $reference,
                    'error_message' => $data['message'] ?? 'Erreur inconnue'
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
                $this->safeLog("Payment URL manquante", [
                    'reference' => $reference,
                    'response_data' => $data
                ]);
                
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
            $this->safeLog("Exception CinetPay", [
                'error' => $e->getMessage(),
                'reference' => $reference ?? 'unknown'
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
            $this->safeLog("Webhook CinetPay: ID manquant", $request->all());
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::with('user', 'messe')->where('reference', $transactionId)->first();

        if (!$paiement) {
            $this->safeLog("Webhook CinetPay: Transaction inconnue", ['transaction_id' => $transactionId]);
            return response()->json(['message' => 'Transaction inconnue'], 404);
        }

        // Vérifier l'état réel via l'API CinetPay
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
                    try {
                        $paiement->user->notify(new PaiementSuccessNotification($paiement));
                    } catch (\Exception $e) {
                        $this->safeLog("Erreur notification succès", [
                            'paiement_id' => $paiement->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                $this->safeLog("Paiement accepté", [
                    'transaction_id' => $transactionId,
                    'paiement_id' => $paiement->id
                ]);
            }
            return response()->json(['message' => 'Paiement validé']);

        } elseif ($statusData['status'] === 'REFUSED') {
            $paiement->update(['statut' => 'echec', 'donnees_transaction' => $statusData]);
            
            if ($paiement->user) {
                try {
                    $paiement->user->notify(new PaiementEchecNotification($paiement));
                } catch (\Exception $e) {
                    $this->safeLog("Erreur notification échec", [
                        'paiement_id' => $paiement->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $this->safeLog("Paiement refusé", [
                'transaction_id' => $transactionId,
                'paiement_id' => $paiement->id
            ]);
            
            return response()->json(['message' => 'Paiement échoué']);
        }

        $this->safeLog("Statut inconnu du paiement", [
            'transaction_id' => $transactionId,
            'status' => $statusData['status']
        ]);
        
        return response()->json(['message' => 'Statut en attente ou inconnu']);
    }

    /**
     * Helper pour vérifier le statut via l'API CinetPay
     */
    public static function verifyCinetPayStatus($transactionId, $siteId)
    {
        try {
            $response = Http::timeout(15)
                ->withOptions(['verify' => false])
                ->post('https://api-checkout.cinetpay.com/v2/payment/check', [
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
            // Utilisation de error_log comme fallback
            error_log("Erreur Check CinetPay: " . $e->getMessage());
            return ['status' => 'ERROR', 'details' => $e->getMessage()];
        }
    }

    /**
     * Méthode de log sécurisée avec fallback
     */
    private function safeLog($message, $context = [])
    {
        try {
            Log::info($message, $context);
        } catch (\Exception $e) {
            // Fallback vers error_log si Laravel log échoue
            error_log("LOG FALLBACK - {$message}: " . json_encode($context));
        }
    }

    /**
     * URL de succès (retour de l'utilisateur)
     */
    public function success(Request $request)
    {
        $transactionId = $request->input('transaction_id') ?? $request->input('cpm_trans_id');
        
        if (!$transactionId) {
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::with('messe')->where('reference', $transactionId)->first();

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        return response()->json([
            'message' => 'Retour succès',
            'statut' => $paiement->statut,
            'paiement' => $paiement
        ]);
    }

    /**
     * URL d'annulation (retour de l'utilisateur)
     */
    public function cancel(Request $request)
    {
        $transactionId = $request->input('transaction_id') ?? $request->input('cpm_trans_id');
        
        if (!$transactionId) {
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::where('reference', $transactionId)->first();

        if ($paiement && $paiement->statut === 'en_attente') {
            $paiement->update(['statut' => 'annule']);
        }

        return response()->json([
            'message' => 'Paiement annulé',
            'statut' => 'annule'
        ]);
    }
}