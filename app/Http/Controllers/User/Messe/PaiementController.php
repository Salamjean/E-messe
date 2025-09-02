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
            // IMPORTANT : S'assurer que Laravel génère des URLs HTTPS pour les callbacks en production.
            // En local, Wave peut refuser les URLs HTTP. Utilise ngrok ou équivalent.
            $redirectUrl = route('user.messe.verification-paiement', $paiement->reference, true); // true force HTTPS si app.url est HTTPS
            
            // Si tu as un setup local sans HTTPS (et sans ngrok), Wave peut rejeter l'URL.
            // Pour le développement local strict, tu peux forcer HTTP ici MAIS CE N'EST PAS RECOMMANDÉ.
            // La WaveService a déjà une logique pour tenter de forcer HTTPS pour Wave.
            // if (app()->environment('local')) {
            //     $redirectUrl = str_replace('https://', 'http://', $redirectUrl);
            // }

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
                $paiement->transaction_id = $session['id']; // C'est l'ID de la session de checkout
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

            Log::debug('Début vérification paiement', [
                'reference' => $reference,
                'statut_actuel_paiement' => $paiement->statut,
                'statut_actuel_messe' => $messe->statut,
                'transaction_id' => $paiement->transaction_id // Important pour le débogage
            ]);

            // Vérifier si le paiement est déjà complété
            if ($paiement->statut === 'paye') {
                DB::commit();
                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement déjà confirmé. Votre demande de messe est traitée.');
            }

            $statusFromUrl = $request->query('status'); // Renommé pour éviter la confusion
            Log::debug('Paramètres de requête URL', ['status' => $statusFromUrl, 'all' => $request->all()]);

            $transactionId = $paiement->transaction_id;

            // Si pas d'ID de transaction, c'est une erreur de logique précédente
            if (empty($transactionId)) {
                DB::rollBack();
                Log::error('ID de transaction Wave manquant pour le paiement', ['paiement_id' => $paiement->id, 'reference' => $reference]);
                return redirect()->route('user.messe.paiement', $reference)
                    ->with('error', 'Erreur: ID de transaction Wave manquant. Veuillez contacter le support.');
            }

            // Vérifier le statut avec l'API Wave en utilisant l'ID de session de checkout
            $waveSession = $this->waveService->verifyTransaction($transactionId);
            Log::debug('Résultat vérification Wave (session)', ['wave_session' => $waveSession]);

            if ($waveSession) {
                // La documentation Wave te dira où trouver le vrai statut de la transaction dans la session de checkout
                // Supposons qu'il y ait un champ 'status' au niveau racine de la session ou dans une sous-structure.
                // OU, si la session contient un objet 'transaction' avec son propre statut.
                $waveStatus = $waveSession['status'] ?? ($waveSession['transaction']['status'] ?? null);

                if ($waveStatus === 'completed' || $waveStatus === 'succeeded' || $waveStatus === 'success') { // Ajout de 'succeeded' si Wave l'utilise
                    // Paiement réussi - Mettre à jour les deux tables
                    $paiement->statut = 'paye';
                    $paiement->date_paiement = now();
                    $paiement->donnees_transaction = json_encode($waveSession); // Stocker les données complètes de la session
                    $paiement->save();

                    $messe->statut = 'confirmee';
                    $messe->save();

                    DB::commit();
                    Log::info('Paiement confirmé avec succès', [
                        'reference' => $reference,
                        'paiement_id' => $paiement->id,
                        'messe_id' => $messe->id,
                        'wave_status' => $waveStatus
                    ]);

                    return redirect()->route('user.messe.index')
                        ->with('success', 'Paiement effectué avec succès. Votre demande de messe est confirmée.');
                }
                else if ($waveStatus === 'pending' || $waveStatus === 'initiated' || $waveStatus === 'processing') { // Ajout de 'processing'
                    // Paiement en attente
                    $paiement->statut = 'en_attente';
                    $paiement->save();

                    $messe->statut = 'en_attente_paiement';
                    $messe->save();

                    DB::commit();

                    return view('user.messe.verification', [
                        'paiement' => $paiement,
                        'status' => $waveStatus,
                        'message' => 'Paiement en attente de confirmation.'
                    ]);
                }
                else if ($waveStatus === 'failed' || $waveStatus === 'cancelled' || $waveStatus === 'error') {
                    // Paiement échoué ou annulé
                    $paiement->statut = 'echec';
                    $paiement->save();

                    // Optionnel: Revenir au statut initial ou garder en échec
                    $messe->statut = 'echec_paiement'; // Nouveau statut pour clarifier
                    $messe->save();

                    DB::commit();

                    return redirect()->route('user.messe.index') // Rediriger vers l'index pour voir la liste des messes
                        ->with('error', 'Le paiement a échoué ou a été annulé. Statut Wave: ' . $waveStatus);
                }
                else {
                    // Statut inconnu ou non géré par notre logique
                    Log::warning('Statut Wave inconnu ou non géré', ['wave_status' => $waveStatus, 'reference' => $reference]);
                    DB::rollBack(); // On ne fait rien car on ne sait pas quoi faire
                    return redirect()->route('user.messe.paiement', $reference)
                        ->with('info', 'Le statut du paiement est incertain. Veuillez vérifier dans quelques instants ou contacter le support.');
                }
            }

            // Si aucune session Wave n'a été trouvée avec l'ID, ou si le statut n'est pas clair
            // Ici, nous devrions peut-être nous baser plus sur le `statusFromUrl` seulement si aucune transaction Wave n'est trouvée.
            if ($statusFromUrl === 'success') {
                // Cela peut arriver si Wave redirige avec "success" mais l'API est en retard ou ne renvoie rien.
                // On peut essayer de relancer la vérification après un petit délai, mais ici, on a déjà essayé.
                // Si waveSession est null ici, c'est que verifyTransaction a échoué.
                // Dans ce cas, il est mieux de considérer comme en attente ou erreur.
                Log::warning('Redirection avec succès mais pas de réponse Wave ou réponse non concluante.', [
                    'reference' => $reference,
                    'transaction_id' => $transactionId
                ]);
                
                // Mettre à jour en "en attente" si la transaction Wave est introuvable ou pas de statut clair
                $paiement->statut = 'en_attente';
                $paiement->save();
                $messe->statut = 'en_attente_paiement';
                $messe->save();
                DB::commit();
                
                return view('user.messe.verification', [
                    'paiement' => $paiement,
                    'status' => 'en_attente',
                    'message' => 'Le paiement est en cours de traitement. Veuillez patienter.'
                ]);

            } elseif ($statusFromUrl === 'error' || $statusFromUrl === 'cancel') {
                $paiement->statut = 'echec';
                $paiement->save();
                $messe->statut = 'echec_paiement'; // Nouveau statut
                $messe->save();
                DB::commit();

                return redirect()->route('user.messe.index')
                    ->with('error', 'Le paiement a été annulé ou a échoué. Veuillez réessayer.');
            }

            DB::commit(); // S'il n'y a pas eu de changement, on commit la transaction DB vide (ou on pourrait rollBack)
            return redirect()->route('user.messe.paiement', $reference)
                ->with('info', 'Paiement en cours de traitement ou statut inconnu. Veuillez actualiser dans quelques instants.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur verifierPaiement: ' . $e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString()
            ]);

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

            $transactionId = $paiement->transaction_id;

            if (empty($transactionId)) {
                DB::rollBack();
                return back()->with('error', 'ID de transaction Wave manquant pour la vérification manuelle.');
            }

            $waveSession = $this->waveService->verifyTransaction($transactionId);

            if ($waveSession) {
                $waveStatus = $waveSession['status'] ?? ($waveSession['transaction']['status'] ?? null);

                if ($waveStatus === 'completed' || $waveStatus === 'succeeded' || $waveStatus === 'success') {
                    // Paiement réussi
                    $paiement->statut = 'paye';
                    $paiement->date_paiement = now();
                    $paiement->donnees_transaction = json_encode($waveSession);
                    $paiement->save();

                    $messe->statut = 'confirmee';
                    $messe->save();

                    DB::commit();

                    return redirect()->route('user.messe.index')
                        ->with('success', 'Paiement vérifié et confirmé avec succès.');
                } else {
                    DB::commit();
                    return back()->with('info', 'Le paiement est toujours en attente ou a échoué. Statut: ' . $waveStatus);
                }
            }

            DB::commit();
            return back()->with('error', 'Impossible de vérifier le statut du paiement via Wave API.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur verifierManuellement: ' . $e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de la vérification manuelle: ' . $e->getMessage());
        }
    }
}