<?php

namespace App\Http\Controllers\User\Messe;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Models\Paiement;
use App\Notifications\PaiementSuccessNotification;
use App\Services\WaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    protected $waveService;

    public function __construct(WaveService $waveService)
    {
        $this->waveService = $waveService;
    }

    /**
     * Afficher le formulaire de paiement
     */
    public function showPaiementForm($reference)
    {
        $paiement = Paiement::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->with('messe')
            ->firstOrFail();

        $messe = $paiement->messe;
        $montantTotal = $paiement->montant;

        return view('user.messe.paiement', compact('paiement', 'messe', 'montantTotal'));
    }

    /**
     * Initialiser le paiement Wave
     */
    public function initierPaiement(Request $request, $reference)
    {
        try {
            DB::beginTransaction();

            $paiement = Paiement::where('reference', $reference)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Vérifier si le paiement n'est pas déjà traité
            if ($paiement->statut === 'paye') {
                DB::rollBack();

                return redirect()->route('user.messe.index')
                    ->with('info', 'Ce paiement a déjà été traité.');
            }

            $montantTotal = $paiement->montant;

            // URLs de retour Wave
            $successUrl = route('wave.success', ['ref' => $paiement->reference]);
            $errorUrl = route('wave.error', ['ref' => $paiement->reference]);

            // Initialiser la session de paiement avec Wave
            $result = $this->waveService->createCheckoutSession(
                $montantTotal,
                'XOF',
                $paiement->reference,
                $successUrl,
                $errorUrl,
                [
                    'messe_id' => $paiement->messe_id,
                    'user_id' => Auth::id(),
                    'reference' => $paiement->reference,
                ]
            );

            if ($result['success'] && !empty($result['wave_launch_url'])) {
                $paiement->transaction_id = $result['session_id'] ?? $paiement->reference;
                $paiement->donnees_transaction = json_encode($result['data']);
                $paiement->methode = 'wave';
                $paiement->statut = 'en_attente';
                $paiement->save();

                $messe = $paiement->messe;
                $messe->statut = 'en_attente_paiement';
                $messe->save();

                DB::commit();

                return redirect($result['wave_launch_url']);
            }

            DB::rollBack();
            Log::error('Erreur initialisation Wave', ['result' => $result]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'initialisation du paiement Wave : ' . ($result['message'] ?? 'Veuillez réessayer.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur initierPaiement Wave: ' . $e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur technique s\'est produite : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier le statut du paiement Wave
     */
    public function verifierPaiement(Request $request, $reference)
    {
        try {
            DB::beginTransaction();

            $paiement = Paiement::where('reference', $reference)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $messe = $paiement->messe;

            Log::debug('Vérification paiement Wave', [
                'reference' => $reference,
            ]);

            // Vérifier si déjà payé
            if ($paiement->statut === 'paye') {
                DB::commit();

                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement déjà confirmé.');
            }

            // Vérification de session Wave
            $isPaid = false;
            $sessionId = $paiement->transaction_id;

            if ($sessionId && str_starts_with($sessionId, 'cos-')) {
                $sessionData = $this->waveService->verifyBySessionId($sessionId);

                if ($sessionData && (
                    ($sessionData['checkout_status'] ?? null) === 'complete' ||
                    in_array($sessionData['payment_status'] ?? '', ['succeeded', 'successful', 'complete', 'paid'])
                )) {
                    $isPaid = true;
                    $paiement->donnees_transaction = json_encode($sessionData);
                }
            }

            if ($isPaid) {
                Log::info('Paiement Wave SUCCESS pour ' . $reference);

                $paiement->statut = 'paye';
                $paiement->methode = 'wave';
                $paiement->date_paiement = now();
                $paiement->save();

                $messe->statut = 'en attente';
                $messe->save();

                DB::commit();

                if ($messe->user && $messe->user->emailNotif) {
                    try {
                        $messe->user->notify(new PaiementSuccessNotification($messe));
                    } catch (\Exception $e) {
                        Log::error("Échec de notification de paiement (Messe #{$messe->id}): " . $e->getMessage());
                    }
                }

                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement Wave effectué avec succès. Votre demande de messe est confirmée.');
            } else {
                DB::commit();

                return redirect()->route('user.messe.paiement', $reference)
                    ->with('error', 'Le paiement Wave n\'a pas encore été validé. Veuillez réessayer ou finaliser sur l\'application Wave.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur verifierPaiement Wave: ' . $e->getMessage());

            return redirect()->route('user.messe.paiement', $reference)
                ->with('error', 'Erreur de vérification : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier manuellement le statut d'un paiement
     */
    public function verifierManuellement($reference)
    {
        return $this->verifierPaiement(request(), $reference);
    }
}
