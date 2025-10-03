<?php

namespace App\Http\Controllers\User\Messe;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaiementStripeController extends Controller
{
    public function initierPaiementStripe($reference)
    {
        try {
            DB::beginTransaction();
            
            $paiement = Paiement::where('reference', $reference)->firstOrFail();
            
            // Vérifier si le paiement n'est pas déjà traité
            if ($paiement->statut === 'paye') {
                DB::rollBack();
                return redirect()->route('user.messe.index')
                    ->with('info', 'Ce paiement a déjà été traité.');
            }
            
            // Calculer les frais de 2% et le montant total
            $fraisService = $paiement->montant * 0.02;
            $montantAvecFrais = $paiement->montant + $fraisService;
            
            // Stocker les frais dans les données de transaction
            $donneesAvecFrais = [
                'montant_initial' => $paiement->montant,
                'frais_service' => $fraisService,
                'montant_total' => $montantAvecFrais,
                'taux_frais' => '2%'
            ];
            
            // Configuration de Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'xof',
                        'product_data' => [
                            'name' => 'Demande de messe - ' . $paiement->reference,
                            'description' => 'Demande de messe avec frais de service de 2%',
                        ],
                        'unit_amount' => $montantAvecFrais, // Conversion en centimes (Stripe XOF)
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('user.messe.paiement-stripe.success', [
                    'reference' => $reference,
                    'session_id' => '{CHECKOUT_SESSION_ID}'
                ]),
                'cancel_url' => route('user.messe.paiement-stripe.cancel', ['reference' => $reference]),
                'metadata' => [
                    'paiement_id' => $paiement->id,
                    'reference' => $reference,
                    'montant_initial' => $paiement->montant,
                    'frais_service' => $fraisService,
                    'montant_total' => $montantAvecFrais,
                ],
            ]);
            
            // Mettre à jour le paiement avec les informations Stripe
            $paiement->transaction_id = $session->id;
            $paiement->methode = 'stripe';
            $paiement->donnees_transaction = json_encode(array_merge(
                $donneesAvecFrais,
                [
                    'stripe_session_id' => $session->id,
                    'stripe_session_url' => $session->url,
                    'stripe_payment_intent' => $session->payment_intent,
                ]
            ));
            $paiement->save();
            
            // Mettre à jour le statut de la messe
            $paiement->messe->update(['statut' => 'en_attente_paiement']);
            
            DB::commit();
            
            return redirect()->away($session->url);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur initierPaiementStripe: ' . $e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'initialisation du paiement: ' . $e->getMessage());
        }
    }

    public function successPaiementStripe(Request $request, $reference)
    {
        try {
            DB::beginTransaction();
            
            $paiement = Paiement::where('reference', $reference)->firstOrFail();
            $sessionId = $request->session_id;
            
            // Vérifier le statut avec Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = Session::retrieve($sessionId);
            
            if ($session->payment_status === 'paid') {
                // Paiement réussi
                $paiement->update([
                    'statut' => 'paye',
                    'methode' => 'stripe',
                    'date_paiement' => now(),
                    'transaction_id' => $sessionId,
                ]);
                
                // Mettre à jour les données de transaction
                $donneesExistantes = json_decode($paiement->donnees_transaction, true) ?? [];
                $donneesTransaction = array_merge($donneesExistantes, [
                    'stripe_payment_status' => $session->payment_status,
                    'stripe_payment_intent' => $session->payment_intent,
                    'stripe_customer' => $session->customer,
                    'stripe_payment_details' => $session->toArray(),
                ]);
                $paiement->donnees_transaction = json_encode($donneesTransaction);
                $paiement->save();
                
                // Mettre à jour le statut de la messe
                $paiement->messe->update(['statut' => 'en attente']);
                
                DB::commit();
                
                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement effectué avec succès! Votre demande de messe a été confirmée.');
            } else {
                // Paiement non complété
                DB::rollBack();
                return redirect()->route('user.messe.paiement', ['reference' => $reference])
                    ->with('error', 'Le paiement n\'a pas été confirmé. Statut: ' . $session->payment_status);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur successPaiementStripe: ' . $e->getMessage(), [
                'reference' => $reference,
                'session_id' => $request->session_id
            ]);
            
            return redirect()->route('user.messe.paiement', ['reference' => $reference])
                ->with('error', 'Erreur lors de la confirmation du paiement: ' . $e->getMessage());
        }
    }

    public function cancelPaiementStripe($reference)
    {
        try {
            $paiement = Paiement::where('reference', $reference)->firstOrFail();
            
            // Marquer le paiement comme échoué
            $paiement->update([
                'statut' => 'echec',
                'methode' => 'stripe',
            ]);
            
            // Revenir au statut initial pour la messe
            $paiement->messe->update(['statut' => 'en attente']);
            
            return redirect()->route('user.messe.paiement', ['reference' => $reference])
                ->with('error', 'Paiement annulé. Vous pouvez réessayer ou choisir une autre méthode.');
            
        } catch (\Exception $e) {
            Log::error('Erreur cancelPaiementStripe: ' . $e->getMessage(), [
                'reference' => $reference
            ]);
            
            return redirect()->route('user.messe.paiement', ['reference' => $reference])
                ->with('error', 'Erreur lors de l\'annulation du paiement.');
        }
    }

    /**
     * Vérifier manuellement un paiement Stripe
     */
    public function verifierPaiementStripe($reference)
    {
        try {
            DB::beginTransaction();
            
            $paiement = Paiement::where('reference', $reference)->firstOrFail();
            
            // Vérifier si déjà payé
            if ($paiement->statut === 'paye') {
                DB::commit();
                return back()->with('info', 'Le paiement a déjà été confirmé.');
            }
            
            if (!$paiement->transaction_id) {
                DB::commit();
                return back()->with('error', 'Aucune transaction Stripe trouvée.');
            }
            
            // Vérifier avec Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = Session::retrieve($paiement->transaction_id);
            
            if ($session->payment_status === 'paid') {
                // Paiement réussi
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                ]);
                
                // Mettre à jour les données de transaction
                $donneesExistantes = json_decode($paiement->donnees_transaction, true) ?? [];
                $donneesTransaction = array_merge($donneesExistantes, [
                    'stripe_payment_status' => $session->payment_status,
                    'stripe_verification' => $session->toArray(),
                ]);
                $paiement->donnees_transaction = json_encode($donneesTransaction);
                $paiement->save();
                
                // Mettre à jour le statut de la messe
                $paiement->messe->update(['statut' => 'en attente']);
                
                DB::commit();
                
                return redirect()->route('user.messe.index')
                    ->with('success', 'Paiement vérifié et confirmé avec succès.');
            } else {
                DB::commit();
                return back()->with('info', 'Le paiement est toujours en attente. Statut Stripe: ' . $session->payment_status);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur verifierPaiementStripe: ' . $e->getMessage(), [
                'reference' => $reference
            ]);
            
            return back()->with('error', 'Erreur lors de la vérification: ' . $e->getMessage());
        }
    }
}