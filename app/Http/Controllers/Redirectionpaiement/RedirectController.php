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

    // 🔹 Vérifier si déjà payé pour éviter les doublons
    if ($paiement->statut !== 'paye') {
        $paiement->update(['statut' => 'paye']);

        // 🔹 Mettre à jour la messe associée
        $messe = $paiement->messe;
        if ($messe) {
            $messe->update(['statut' => 'en attente']);
        }
    }

    // 🔹 Redirection vers ton app mobile (Android/iOS)
    $redirectUrl = "maparoisse://paiement?status=success&reference={$paiement->reference}";

    return response()->view('paiement.success', compact('redirectUrl', 'paiement'));
}


/**
 * ❌ Redirection après échec ou annulation de paiement
 */
public function error(Request $request)
{
    $reference = $request->query('ref');

    if (!$reference) {
        return response()->view('paiement.erreur', [
            'message' => "Référence de paiement manquante."
        ]);
    }

    $paiement = Paiement::where('reference', $reference)->first();

    if ($paiement) {
        // 🔹 Mettre à jour le paiement
        $paiement->update(['statut' => 'echoue']);

        // 🔹 Mettre la messe en attente de paiement
        $messe = $paiement->messe;
        if ($messe) {
            $messe->update(['statut' => 'en_attente_paiement']);
        }
    }

    $redirectUrl = "maparoisse://paiement?status=error&ref={$reference}";

    return response()->view('paiement.erreur', [
        'redirectUrl' => $redirectUrl,
        'message' => "Le paiement n’a pas abouti. Veuillez réessayer."
    ]);
}

}
