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
        Log::info('=== DÉBUT INITIATION PAIEMENT CINETPAY ===');
        
        $request->validate([
            'messe_id' => 'required|exists:messes,id',
            'montant'  => 'required|numeric|min:100',
        ]);

        $user = $request->user();
        $messe = Messe::findOrFail($request->messe_id);

        // Référence unique
        $reference = 'MESSE_CINET_' . time() . '_' . $user->id;

        try {
            // 1. Création locale du paiement
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id'  => $user->id,
                'reference'=> $reference,
                'montant'  => $request->montant,
                'devise'   => 'XOF',
                'methode'  => 'cinetpay',
                'statut'   => 'en_attente',
            ]);

            // 2. URLs de callback
            $returnUrl = route('cinetpay.success', ['transaction_id' => $reference]);
            $cancelUrl = route('cinetpay.cancel', ['transaction_id' => $reference]);
            $notifyUrl = route('cinetpay.webhook');

            // Vérification Localhost (Warning)
            if (str_contains($notifyUrl, 'localhost') || str_contains($notifyUrl, '127.0.0.1')) {
                Log::warning('⚠️ ATTENTION: Webhook en localhost détecté. Utilisez Ngrok pour que CinetPay puisse vous notifier.');
            }

            // 3. NETTOYAGE ET FORMATAGE DU TÉLÉPHONE (CRITIQUE)
            $prefixPays = '225'; // Par défaut Côte d'Ivoire
            $rawPhone = $user->phone ?? '0707070707'; // Numéro par défaut valide si inexistant
            
            // Enlever tout ce qui n'est pas un chiffre
            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
            
            // Si le numéro fait 10 chiffres (ex: 0707070707), on ajoute 225
            if (strlen($cleanPhone) === 10) {
                $cleanPhone = $prefixPays . $cleanPhone;
            }
            // Si le numéro est vide ou invalide, on met un numéro générique de test
            if (strlen($cleanPhone) < 8) {
                $cleanPhone = '2250707070707';
            }

            // 4. Préparation des données pour CinetPay
            $paymentData = [
                'apikey'          => env('CINETPAY_API_KEY'),
                'site_id'         => env('CINETPAY_SITE_ID'),
                'transaction_id'  => $reference,
                'amount'          => (int)$request->montant,
                'currency'        => 'XOF',
                'description'     => 'Offrande de Messe',
                'return_url'      => $returnUrl,
                'cancel_url'      => $cancelUrl,
                'notify_url'      => $notifyUrl,
                // Utilisation de la variable d'environnement ou TEST par défaut
                'mode'            => env('CINETPAY_MODE', 'TEST'),
                'channels'        => 'ALL',
                
                // Informations Client
                'customer_name'   => $user->name ?? 'Fidele', 
                'customer_surname'=> $user->name ?? 'Messe',
                'customer_email'  => $user->email ?? 'no-reply@sancta-missa.com',
                'customer_phone_number' => $cleanPhone, // Numéro corrigé
                'customer_address' => $user->adresse ?? 'Abidjan',
                'customer_city'   => $user->ville ?? 'Abidjan',
                'customer_country'=> 'CI',
                'customer_zip_code'=> '00225',
            ];

            Log::info('📤 Envoi Données CinetPay', $paymentData);

            // 5. Appel API CinetPay
            $response = Http::withoutVerifying()->post('https://api-checkout.cinetpay.com/v2/payment', $paymentData);
            $data = $response->json();

            // 6. Gestion du retour
            if ($response->failed() || ($data['code'] ?? '') !== '201') {
                Log::error('❌ Erreur CinetPay', ['response' => $data]);
                
                $paiement->update(['statut' => 'echec', 'donnees_transaction' => $data]);

                return response()->json([
                    'message' => "Erreur lors de l'initialisation du paiement.",
                    'error_cinetpay' => $data['description'] ?? 'Erreur inconnue',
                    'debug' => $data // Utile pour voir l'erreur exacte
                ], 400);
            }

            // Succès : Mise à jour avec les infos reçues
            $paiement->update(['donnees_transaction' => $data]);

            return response()->json([
                'statut'       => 'success',
                'reference'    => $reference,
                'checkout_url' => $data['data']['payment_url'],
            ]);

        } catch (\Exception $e) {
            Log::error('💥 Exception Paiement', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'message' => 'Erreur serveur interne',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook appelé par CinetPay
     */
    public function handleWebhook(Request $request)
    {
        $transactionId = $request->input('cpm_trans_id') ?? $request->input('transaction_id');

        if (!$transactionId) {
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::where('reference', $transactionId)->first();

        if (!$paiement) {
            return response()->json(['message' => 'Transaction inconnue'], 404);
        }

        // Vérification statut API
        $statusData = $this->verifyCinetPayStatus($transactionId, $paiement->site_id ?? env('CINETPAY_SITE_ID'));

        // Mise à jour si succès
        if (($statusData['status'] ?? '') === 'ACCEPTED') {
            if ($paiement->statut !== 'paye') {
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                    'donnees_transaction' => array_merge($paiement->donnees_transaction ?? [], $statusData),
                ]);

                if ($paiement->messe) {
                    $paiement->messe->update(['statut' => 'en attente']);
                }

                try {
                    if ($paiement->user) {
                        $paiement->user->notify(new PaiementSuccessNotification($paiement));
                    }
                } catch (\Exception $e) {
                    Log::error("Erreur Notification: " . $e->getMessage());
                }
            }
            return response()->json(['message' => 'Paiement validé']);
        } 
        elseif (($statusData['status'] ?? '') === 'REFUSED') {
            $paiement->update(['statut' => 'echec', 'donnees_transaction' => $statusData]);
            return response()->json(['message' => 'Paiement échoué']);
        }

        return response()->json(['message' => 'Statut reçu: ' . ($statusData['status'] ?? 'Inconnu')]);
    }

    /**
     * Helper Vérification
     */
    public static function verifyCinetPayStatus($transactionId, $siteId)
    {
        try {
            $response = Http::withoutVerifying()->post('https://api-checkout.cinetpay.com/v2/payment/check', [
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