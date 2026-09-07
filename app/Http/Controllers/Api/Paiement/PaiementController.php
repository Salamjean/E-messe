<?php

namespace App\Http\Controllers\Api\Paiement;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Models\Paiement;
use App\Services\WaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    protected $waveService;

    public function __construct(WaveService $waveService)
    {
        $this->waveService = $waveService;
    }

    /**
     * Initialiser un paiement via Wave (Compatible avec les anciens appels CinetPay de l'app mobile)
     */
    public function initierPaiement(Request $request)
    {
        Log::info('=== DÉBUT INITIATION PAIEMENT WAVE (API) ===');

        $request->validate([
            'messe_id' => 'required|exists:messes,id',
            'montant' => 'required|numeric|min:100',
        ]);

        $user = $request->user();
        $messe = Messe::findOrFail($request->messe_id);

        $reference = 'MESSE_WAVE_' . time() . '_' . $user->id;

        try {
            // Création locale du paiement
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id' => $user->id,
                'reference' => $reference,
                'montant' => $request->montant,
                'devise' => 'XOF',
                'methode' => 'wave',
                'statut' => 'en_attente',
            ]);

            $successUrl = route('wave.success', ['ref' => $reference]);
            $errorUrl = route('wave.error', ['ref' => $reference]);

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

            if ($result['success'] && !empty($result['wave_launch_url'])) {
                $paiement->update([
                    'transaction_id' => $result['session_id'] ?? $reference,
                    'donnees_transaction' => $result['data'] ?? [],
                ]);

                $messe->update(['statut' => 'en_attente_paiement']);

                return response()->json([
                    'statut' => 'success',
                    'status' => 'success',
                    'reference' => $reference,
                    'payment_url' => $result['wave_launch_url'],
                    'checkout_url' => $result['wave_launch_url'],
                    'wave_launch_url' => $result['wave_launch_url'],
                    'session_id' => $result['session_id'] ?? null,
                    'message' => 'Session de paiement Wave initiée avec succès.',
                ], 200);
            }

            Log::error('Erreur Wave Init (API)', ['result' => $result]);

            return response()->json([
                'statut' => 'error',
                'message' => 'Erreur lors de l\'initialisation du paiement Wave',
                'details' => $result['message'] ?? 'Erreur inconnue',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Exception Paiement Wave (API): ' . $e->getMessage());

            return response()->json([
                'statut' => 'error',
                'message' => 'Erreur Serveur',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook de compatibilité
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Webhook reçu sur PaiementController (API):', $request->all());

        return response()->json(['message' => 'Webhook reçu']);
    }

    /**
     * Retour succès
     */
    public function paymentSuccess(Request $request)
    {
        return response()->json(['message' => 'Paiement Wave validé ou en cours de traitement.']);
    }

    /**
     * Retour annulation
     */
    public function paymentCancel(Request $request)
    {
        return response()->json(['message' => 'Paiement Wave annulé par l\'utilisateur.']);
    }
}
