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
        
        return view('user.messe.paiement', compact('paiement', 'messe'));
    }

    /**
     * Initialiser le paiement Wave - Version corrigée
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
        
        // Créer la session de paiement Wave
        $redirectUrl = route('user.messe.verification-paiement', $paiement->reference);
        
        // FORCER HTTP EN ENVIRONNEMENT LOCAL
        if (app()->environment('local')) {
            $redirectUrl = str_replace('https://', 'http://', $redirectUrl);
        }
        
        $session = $this->waveService->createCheckoutSession(
            $paiement->montant,
            $paiement->devise,
            $paiement->reference,
            $redirectUrl,
            [
                'email' => $paiement->messe->email_demandeur,
                'name' => $paiement->messe->nom_demandeur,
            ]
        );
        
        if ($session && isset($session['id'])) {
            // Mettre à jour le paiement avec l'ID de session Wave
            $paiement->transaction_id = $session['id'];
            $paiement->donnees_transaction = json_encode($session);
            $paiement->statut = 'en_attente'; // Mettre à jour le statut
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
     * Vérifier le statut du paiement après redirection - Version CORRIGÉE
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
            'statut_url' => $request->query('status')
        ]);
        
        // Vérifier si déjà payé
        if ($paiement->statut === 'paye') {
            DB::commit();
            return redirect()->route('user.messe.index')
                ->with('success', 'Paiement déjà confirmé.');
        }
        
        $status = $request->query('status');
        
        // SOLUTION SIMPLIFIÉE: Utiliser le statut de l'URL de redirection Wave
        if ($status === 'success') {
            Log::debug('Paiement réussi détecté via URL');
            
            // Paiement réussi
            $paiement->statut = 'paye';
            $paiement->date_paiement = now();
            $paiement->save();
            
            $messe->statut = 'confirmee';
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
            Log::debug('Statut inconnu, tentative de vérification via API');
            
            // Essayer l'API Wave comme fallback
            $transaction = $this->waveService->verifyByMerchantReference($reference);
            
            if ($transaction) {
                $waveStatus = $transaction['status'] ?? $transaction['state'] ?? null;
                
                if ($waveStatus === 'completed' || $waveStatus === 'success') {
                    // Paiement réussi via API
                    $paiement->statut = 'paye';
                    $paiement->date_paiement = now();
                    $paiement->donnees_transaction = json_encode($transaction);
                    $paiement->save();
                    
                    $messe->statut = 'confirmee';
                    $messe->save();
                    
                    DB::commit();
                    
                    return redirect()->route('user.messe.index')
                        ->with('success', 'Paiement effectué avec succès.');
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
     * Vérifier manuellement le statut d'un paiement - Version corrigée
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
                    $paiement->donnees_transaction = json_encode($transaction);
                    $paiement->save();
                    
                    $messe->statut = 'confirmee'; // CORRECTION: 'confirmee' avec deux 'e'
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
}