<?php

namespace App\Http\Controllers\User\Messe;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Models\Paiement;
use App\Services\WaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    protected $waveService;

    public function __construct()
    {
        $this->waveService = new WaveService();
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
        
        $montantTotal = $paiement->montant ;

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
            
            // Calculer le montant avec frais de 2%
            $montantAvecFrais = $paiement->montant;
            
            // Stocker les frais dans les données de transaction
            $donneesAvecFrais = [
                'montant_initial' => $paiement->montant,
                'montant_total' => $montantAvecFrais,
                'taux_frais' => '2%'
            ];
            
            // Créer la session de paiement Wave avec le montant total
            $redirectUrl = route('user.messe.verification-paiement', $paiement->reference);
            
            // FORCER HTTP EN ENVIRONNEMENT LOCAL
            if (app()->environment('local')) {
                $redirectUrl = str_replace('https://', 'http://', $redirectUrl);
            }
            
            $session = $this->waveService->createCheckoutSession(
                $montantAvecFrais, // Utiliser le montant avec frais
                $paiement->devise,
                $paiement->reference,
                $redirectUrl,
                [
                    'email' => $paiement->messe->email_demandeur,
                    'name' => $paiement->messe->nom_demandeur,
                ]
            );
            
            if ($session && isset($session['id'])) {
                // Combiner les données Wave avec nos données de frais
                $donneesTransaction = array_merge($session, $donneesAvecFrais);
                
                // Mettre à jour le paiement avec l'ID de session Wave et les frais
                $paiement->transaction_id = $session['id'];
                $paiement->donnees_transaction = json_encode($donneesTransaction);
                $paiement->statut = 'en_attente';
                $paiement->save();
                
                // Mettre à jour le statut de la messe
                $messe = $paiement->messe;
                $messe->statut = 'en_attente_paiement';
                $messe->save();
                
                DB::commit();
                
                // Rediriger vers la page de paiement Wave
                $redirectUrl = $session['wave_launch_url'] ?? null;
                
                if ($redirectUrl) {
                    return redirect($redirectUrl);
                } else {
                    Log::error('URL de redirection Wave manquante', ['session' => $session]);
                    return redirect()->back()
                        ->with('error', 'Erreur technique lors de la redirection. Veuillez réessayer.');
                }
            }
            
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur initierPaiement: ' . $e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur technique s\'est produite: ' . $e->getMessage());
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
            
            Log::debug('Vérification paiement', [
                'reference' => $reference,
                'session_id' => $paiement->transaction_id,
                'statut_url' => $request->query('status')
            ]);
            
            // Vérifier si déjà payé
            if ($paiement->statut === 'paye') {
                DB::commit();
                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement déjà confirmé.');
            }
            
            $status = $request->query('status');
            
            if ($status === 'success') {
                Log::debug('Paiement réussi détecté via URL');
                
                // Vérifier avec l'API Wave pour confirmation
                $waveStatus = null;
                if ($paiement->transaction_id) {
                    $session = $this->waveService->verifyBySessionId($paiement->transaction_id);
                    
                    if ($session) {
                        $waveStatus = $session['status'] ?? $session['state'] ?? $session['payment_status'] ?? null;
                        Log::debug('Statut Wave détecté: ' . $waveStatus);
                        
                        // Mettre à jour les données de transaction avec le statut Wave
                        $donneesExistantes = json_decode($paiement->donnees_transaction, true) ?? [];
                        $donneesTransaction = array_merge($donneesExistantes, [
                            'wave_status' => $waveStatus,
                            'wave_verification' => $session
                        ]);
                        $paiement->donnees_transaction = json_encode($donneesTransaction);
                    }
                }
                
                // Paiement réussi
                $paiement->statut = 'paye';
                $paiement->date_paiement = now();
                $paiement->save();
                
                $messe->statut = 'en attente';
                $messe->save();
                
                DB::commit();
                
                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement effectué avec succès. Votre demande de messe est confirmée.');
            }
            elseif ($status === 'error' || $status === 'cancel') {
                Log::debug('Paiement échoué détecté via URL');
                
                // Paiement échoué
                $paiement->statut = 'echec';
                $paiement->save();
                
                $messe->statut = 'en attente';
                $messe->save();
                
                DB::commit();
                
                return redirect()->route('user.messe.paiement', $reference)
                    ->with('error', 'Le paiement a échoué. Veuillez réessayer.');
            }
            else {
                Log::debug('Aucun statut dans URL, vérification via API Wave');
                
                // Essayer avec le session ID
                if ($paiement->transaction_id) {
                    $session = $this->waveService->verifyBySessionId($paiement->transaction_id);
                    
                    if ($session) {
                        $waveStatus = $session['status'] ?? $session['state'] ?? $session['payment_status'] ?? 'inconnu';
                        Log::debug('Statut session Wave: ' . $waveStatus);
                        
                        if (in_array($waveStatus, ['completed', 'success', 'paid', 'succeeded'])) {
                            // Paiement réussi via API
                            $paiement->statut = 'paye';
                            $paiement->date_paiement = now();
                            
                            // Mettre à jour les données de transaction
                            $donneesExistantes = json_decode($paiement->donnees_transaction, true) ?? [];
                            $donneesTransaction = array_merge($donneesExistantes, [
                                'wave_status' => $waveStatus,
                                'wave_verification' => $session
                            ]);
                            $paiement->donnees_transaction = json_encode($donneesTransaction);
                            $paiement->save();
                            
                            $messe->statut = 'en attente';
                            $messe->save();
                            
                            DB::commit();
                            
                            return redirect()->route('user.messe.index')
                                ->with('success', 'Paiement effectué avec succès.');
                        }
                    }
                }
                
                // Si on arrive ici, le paiement est en attente
                DB::commit();
                return view('user.messe.verification', compact('paiement'))
                    ->with('info', 'Paiement en cours de traitement. Veuillez actualiser dans quelques instants.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur verifierPaiement: ' . $e->getMessage());
            return redirect()->route('user.messe.paiement', $reference)
                ->with('error', 'Erreur de vérification: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier manuellement le statut d'un paiement
     */
    public function verifierManuellement($reference)
    {
        try {
            DB::beginTransaction();
            
            $paiement = Paiement::where('reference', $reference)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $messe = $paiement->messe;
            
            // Vérifier si déjà payé
            if ($paiement->statut === 'paye') {
                DB::commit();
                return back()->with('info', 'Le paiement a déjà été confirmé.');
            }
            
            $transaction = $this->waveService->verifyByMerchantReference($reference);
            
            if ($transaction) {
                if ($transaction['status'] === 'completed') {
                    // Paiement réussi
                    $paiement->statut = 'paye';
                    $paiement->date_paiement = now();
                    
                    // Mettre à jour les données de transaction
                    $donneesExistantes = json_decode($paiement->donnees_transaction, true) ?? [];
                    $donneesTransaction = array_merge($donneesExistantes, [
                        'wave_status' => 'completed',
                        'wave_verification' => $transaction
                    ]);
                    $paiement->donnees_transaction = json_encode($donneesTransaction);
                    $paiement->save();
                    
                    $messe->statut = 'en attente';
                    $messe->save();
                    
                    DB::commit();
                    
                    return redirect()->route('user.messe.index')
                        ->with('success', 'Paiement vérifié et confirmé avec succès.');
                } else {
                    DB::commit();
                    return back()->with('info', 'Le paiement est toujours en attente. Statut: ' . $transaction['status']);
                }
            }
            
            DB::commit();
            return back()->with('error', 'Impossible de vérifier le statut du paiement.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur verifierManuellement: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la vérification: ' . $e->getMessage());
        }
    }

    /**
     * Méthode utilitaire pour récupérer les détails des frais
     */
    private function getFraisDetails($paiement)
    {
        $donnees = json_decode($paiement->donnees_transaction, true) ?? [];
        
        return [
            'montant_initial' => $donnees['montant_initial'] ?? $paiement->montant,
            'frais_service' => $donnees['frais_service'] ?? ($paiement->montant * 0.02),
            'montant_total' => $donnees['montant_total'] ?? ($paiement->montant + ($paiement->montant * 0.02)),
            'taux_frais' => $donnees['taux_frais'] ?? '2%'
        ];
    }
}