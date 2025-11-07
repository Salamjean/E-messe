<?php

namespace App\Http\Controllers\Redirectionpaiement;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    /**
     * ✅ Redirection après succès de paiement
     */
    public function success(Request $request)
    {
        $reference = $request->query('ref');

        if (!$reference) {
            return response()->view('paiement.erreur', [
                'message' => "Référence de paiement manquante."
            ]);
        }

        $paiement = Paiement::where('reference', $reference)->first();

        if (!$paiement) {
            return response()->view('paiement.erreur', [
                'message' => "Paiement introuvable."
            ]);
        }

        // Mettre à jour le statut si ce n’est pas déjà fait
        $paiement->update(['statut' => 'paye']);

        // URL de redirection vers l’app mobile (Android/iOS)
        // sanctaapp://paiement?status=success&ref=MESSE_WAVE_123
        $redirectUrl = "maparoisse://paiement?status=success&reference={$paiement->reference}";

        // Afficher une page web avec redirection automatique vers l’app
        return response()->view('paiement.success', compact('redirectUrl', 'paiement'));
    }

    /**
     * ❌ Redirection après échec de paiement
     */
    public function error(Request $request)
    {
        $reference = $request->query('ref');

        $paiement = Paiement::where('reference', $reference)->first();
        if ($paiement) {
            $paiement->update(['statut' => 'echoue']);
        }

        $redirectUrl = "maparoisse://paiement?status=error&ref={$reference}";

        return response()->view('paiement.erreur', [
            'redirectUrl' => $redirectUrl,
            'message' => "Le paiement n’a pas abouti. Veuillez réessayer."
        ]);
    }
}
