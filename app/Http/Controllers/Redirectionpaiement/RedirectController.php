<?php

namespace App\Http\Controllers\Redirectionpaiement;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Notifications\PaiementSuccessNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    /**
     * ✅ Redirection après succès de paiement Wave
     */
    public function success(Request $request)
    {
        $reference = $request->query('ref') ?? $request->query('reference') ?? $request->query('client_reference');

        if (!$reference) {
            return response()->view('paiement.erreur', [
                'message' => "Référence de paiement manquante."
            ]);
        }

        $paiement = Paiement::with('messe.user')->where('reference', $reference)->first();

        if (!$paiement) {
            return response()->view('paiement.erreur', [
                'message' => "Paiement introuvable."
            ]);
        }

        // 🔹 Marquer comme payé
        if ($paiement->statut !== 'paye') {
            $paiement->update([
                'statut' => 'paye',
                'methode' => 'wave',
                'date_paiement' => now(),
            ]);

            $messe = $paiement->messe;
            if ($messe) {
                $messe->update(['statut' => 'en attente']);

                // Notification par email
                if ($messe->user && $messe->user->emailNotif) {
                    try {
                        $messe->user->notify(new PaiementSuccessNotification($messe));
                    } catch (\Exception $e) {
                        Log::error("Échec notification paiement (Messe #{$messe->id}): " . $e->getMessage());
                    }
                }
            }
        }

        // 🔹 Si l'utilisateur est connecté via le web, redirection directe vers son reçu
        if (Auth::guard('web')->check() && $paiement->messe_id) {
            return redirect()->route('user.messe.receipt', $paiement->messe_id)
                ->with('success', 'Votre offrande pour la messe a été payée avec succès via Wave.');
        }

        // 🔹 Sinon redirection vers l'app mobile (Deep Link)
        $redirectUrl = "maparoisse://paiement?status=success&reference={$paiement->reference}";

        return response()->view('paiement.success', compact('redirectUrl', 'paiement'));
    }

    /**
     * ❌ Redirection après échec ou annulation de paiement Wave
     */
    public function error(Request $request)
    {
        $reference = $request->query('ref') ?? $request->query('reference') ?? $request->query('client_reference');

        if (!$reference) {
            return response()->view('paiement.erreur', [
                'message' => "Référence de paiement manquante."
            ]);
        }

        $paiement = Paiement::where('reference', $reference)->first();

        if ($paiement) {
            $paiement->update(['statut' => 'echoue']);

            $messe = $paiement->messe;
            if ($messe) {
                $messe->update(['statut' => 'en_attente_paiement']);
            }

            // Si utilisateur web, retour sur la page de paiement
            if (Auth::guard('web')->check()) {
                return redirect()->route('user.messe.paiement', $paiement->reference)
                    ->with('error', 'Le paiement Wave a été annulé ou n’a pas abouti. Veuillez réessayer.');
            }
        }

        $redirectUrl = "maparoisse://paiement?status=error&ref={$reference}";

        return response()->view('paiement.erreur', [
            'redirectUrl' => $redirectUrl,
            'message' => "Le paiement n’a pas abouti. Veuillez réessayer."
        ]);
    }
}
