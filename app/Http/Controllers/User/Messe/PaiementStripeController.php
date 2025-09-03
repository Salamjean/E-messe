<?php

namespace App\Http\Controllers\User\Messe;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaiementStripeController extends Controller
{
    public function initierPaiementStripe($reference)
    {
        $paiement = Paiement::where('reference', $reference)->firstOrFail();
        
        // Configuration de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'xof',
                        'product_data' => [
                            'name' => 'Demande de messe - ' . $paiement->reference,
                        ],
                        'unit_amount' => $paiement->montant * 1, // Conversion en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('user.messe.paiement.success', ['reference' => $reference]),
                'cancel_url' => route('user.messe.paiement.cancel', ['reference' => $reference]),
                'metadata' => [
                    'paiement_id' => $paiement->id,
                    'reference' => $reference,
                ],
            ]);
            
            return redirect()->away($session->url);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'initialisation du paiement: ' . $e->getMessage());
        }
    }

    public function successPaiementStripe(Request $request, $reference)
    {
        $paiement = Paiement::where('reference', $reference)->firstOrFail();
        
        // Mettre à jour le statut du paiement
        $paiement->update([
            'statut' => 'paye',
            'methode' => 'stripe',
            'date_paiement' => now(),
            'transaction_id' => $request->session_id ?? null,
        ]);
        
        // Mettre à jour le statut de la messe
        $paiement->messe->update(['statut' => 'confirme']);
        
        return redirect()->route('user.messe.index')
            ->with('success', 'Paiement effectué avec succès! Votre demande de messe a été confirmée.');
    }

    public function cancelPaiementStripe($reference)
    {
        return redirect()->route('user.messe.paiement', ['reference' => $reference])
            ->with('error', 'Paiement annulé. Vous pouvez réessayer ou choisir une autre méthode.');
    }
}
