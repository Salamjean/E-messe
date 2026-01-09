<?php

namespace App\Http\Controllers\User\Messe;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Models\Paiement;
use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    protected $cinetPayService;

    public function __construct(CinetPayService $cinetPayService)
    {
        $this->cinetPayService = $cinetPayService;
    }

    /**
     * Afficher le formulaire de paiement
     */
    public function showPaiementForm($reference)
    {
        $paiement = Paiement::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $messe = $paiement->messe;

        // Calculer les frais de 2% et le montant total

        $montantTotal = $paiement->montant;

        // dd($montantTotal, $paiement);

        return view('user.messe.paiement', compact('paiement', 'messe', 'montantTotal'));
    }

    /**
     * Initialiser le paiement Wave avec frais
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

            // Calculer le montant total
            $montantTotal = $paiement->montant;

            // URLs pour CinetPay
            $urls = [
                'notify_url' => route('cinetpay.success'), 
                'return_url' => route('user.messe.verification-paiement', $paiement->reference),
                'cancel_url' => route('user.messe.paiement', $paiement->reference),
            ];

            // Infos client
            $customerInfo = [
                'nom' => $paiement->messe->nom_demandeur ?? 'Fidele',
                'prenom' => '',
                'email' => $paiement->messe->email_demandeur,
                'telephone' => $paiement->messe->telephone_demandeur,
            ];

            // Initialiser le paiement avec CinetPay
            $result = $this->cinetPayService->initPayment(
                $paiement->reference,
                $montantTotal,
                $urls,
                $customerInfo
            );

            if ($result['success']) {
                $paiement->transaction_id = $paiement->reference;
                $paiement->donnees_transaction = json_encode($result['api_response']);
                $paiement->statut = 'en_attente';
                $paiement->save();

                $messe = $paiement->messe;
                $messe->statut = 'en_attente_paiement';
                $messe->save();

                DB::commit();

                return redirect($result['payment_url']);
            }

            DB::rollBack();
            Log::error('Erreur initialisation CinetPay', ['result' => $result]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'initialisation du paiement CinetPay : ' . ($result['message'] ?? 'Erreur inconnue'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur initierPaiement: '.$e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur technique s\'est produite: '.$e->getMessage());
        }
    }

    /**
     * Vérifier le statut du paiement
     */
    public function verifierPaiement(Request $request, $reference)
    {
        try {
            DB::beginTransaction();

            $paiement = Paiement::where('reference', $reference)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $messe = $paiement->messe;

            Log::debug('Vérification paiement CinetPay', [
                'reference' => $reference,
            ]);

            // Vérifier si déjà payé
            if ($paiement->statut === 'paye') {
                DB::commit();

                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement déjà confirmé.');
            }

            // Vérifier avec CinetPay
            $statusResponse = $this->cinetPayService->checkStatus($reference);

            if (isset($statusResponse['code']) && $statusResponse['code'] === '00') {
                Log::info('Paiement CinetPay SUCCESS pour ' . $reference);

                $paiement->statut = 'paye';
                $paiement->date_paiement = now();
                $paiement->donnees_transaction = json_encode($statusResponse['data']);
                
                if (isset($statusResponse['data']['payment_method'])) {
                    $paiement->operateur = $statusResponse['data']['payment_method'];
                }
                
                $paiement->save();

                $messe->statut = 'en attente';
                $messe->save();

                DB::commit();

                if ($messe->user && $messe->user->emailNotif) {
                    try {
                        $messe->user->notify(new \App\Notifications\PaiementSuccessNotification($paiement));
                    } catch (\Exception $e) {
                        Log::error("Échec de la notification de paiement (Messe #{$messe->id}): ".$e->getMessage());
                    }
                }

                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement effectué avec succès. Votre demande de messe est confirmée.');
            } else {
                Log::warning('Paiement CinetPay non abouti', ['res' => $statusResponse]);
                DB::commit(); 

                return redirect()->route('user.messe.paiement', $reference)
                    ->with('error', 'Le paiement n\'a pas pu être confirmé ou a été annulé. Statut : ' . ($statusResponse['message'] ?? 'Inconnu'));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur verifierPaiement: '.$e->getMessage());

            return redirect()->route('user.messe.paiement', $reference)
                ->with('error', 'Erreur de vérification: '.$e->getMessage());
        }
    }

    /**
     * Vérifier manuellement le statut d'un paiement
     */
    public function verifierManuellement($reference)
    {
        return $this->verifierPaiement(request(), $reference);
    }

    /**
     * Méthode utilitaire pour récupérer les détails des frais
     */
    private function getFraisDetails($paiement)
    {
        $donnees = json_decode($paiement->donnees_transaction, true) ?? [];
        $montantOffrande = $paiement->messe->montant_offrande ?? ($paiement->montant / 1.04);

        return [
            'montant_initial' => $montantOffrande,
            'frais_service' => $paiement->montant - $montantOffrande,
            'montant_total' => $paiement->montant,
            'taux_frais' => '4% (min 200 FCFA)',
        ];
    }
}
