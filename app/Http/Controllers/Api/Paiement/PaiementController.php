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

        // Référence unique
        $reference = 'MESSE_CINET_' . time() . '_' . $user->id;

        try {
            // 2. Création locale
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id'  => $user->id,
                'reference'=> $reference,
                'montant'  => $request->montant,
                'devise'   => 'XOF',
                'methode'  => 'cinetpay',
                'statut'   => 'en_attente',
            ]);

            // 3. URLs
            $returnUrl = route('cinetpay.success', ['transaction_id' => $reference]);
            $cancelUrl = route('cinetpay.cancel', ['transaction_id' => $reference]);
            $notifyUrl = route('cinetpay.webhook');

            // 4. Appel CinetPay avec Sécurités (Protection données vides)
            $response = Http::withOptions(['verify' => false])
                ->post('https://api-checkout.cinetpay.com/v2/payment', [
                'apikey'          => env('CINETPAY_API_KEY'),
                'site_id'         => env('CINETPAY_SITE_ID'),
                'transaction_id'  => $reference,
                'amount'          => (int)$request->montant,
                'currency'        => 'XOF',
                'description'     => 'Offrande de Messe',
                'return_url'      => $returnUrl,
                'cancel_url'      => $cancelUrl,
                'notify_url'      => $notifyUrl,
                // Fallbacks si l'utilisateur n'a pas de nom ou email
                'customer_name'   => $user->name ?? 'Fidele', 
                'customer_surname'=> $user->name ?? 'Fidele',
                'customer_email'  => $user->email ?? 'no-reply@sancta-missa.com',
                'channels'        => 'ALL'
            ]);

            $data = $response->json();

            // 5. GESTION D'ERREUR PRÉCISE
            if (!isset($data['data']['payment_url'])) {
                Log::error("Erreur Init CinetPay", ['response' => $data]);
                
                // Mettre à jour le paiement en échec
                $paiement->update(['statut' => 'echec', 'donnees_transaction' => $data]);

                return response()->json([
                    'message' => "Erreur CinetPay: " . ($data['description'] ?? 'Erreur inconnue'),
                    'details' => $data // AFFICHE CECI DANS POSTMAN
                ], 400);
            }

            // Succès
            $paiement->update(['donnees_transaction' => $data]);

            return response()->json([
                'statut'       => 'success',
                'reference'    => $reference,
                'checkout_url' => $data['data']['payment_url'],
            ]);

        } catch (\Exception $e) {
            Log::error("Exception CinetPay: " . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur', 'error' => $e->getMessage()], 500);
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