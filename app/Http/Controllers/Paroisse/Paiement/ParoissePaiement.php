<?php

namespace App\Http\Controllers\Paroisse\Paiement;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParoissePaiement extends Controller
{
    public function requestRetrait(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string',
            'numero_compte' => 'required|string',
            'nom_titulaire' => 'required|string',
        ]);
        
        $paroisse = Auth::guard('paroisse')->user();
        
        // Calculer le solde actuel (total des paiements)
        $solde = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'payé')
            ->sum('paiements.montant');
        
        if ($request->montant > $solde) {
            return back()->with('error', 'Le montant demandé dépasse votre solde disponible.');
        }
        
        // Créer la demande de retrait
        $retrait = new ParoisseRetrait();
        $retrait->paroisse_id = $paroisse->id;
        $retrait->montant = $request->montant;
        $retrait->methode = $request->methode;
        $retrait->numero_compte = $request->numero_compte;
        $retrait->nom_titulaire = $request->nom_titulaire;
        $retrait->reference = 'RET' . time() . $paroisse->id;
        $retrait->statut = 'en_attente';
        $retrait->save();
        
        return back()->with('success', 'Votre demande de retrait a été envoyée avec succès.');
    }

    public function retraits()
    {
        $paroisse = Auth::guard('paroisse')->user();
        $retraits = $paroisse->retraits()->orderBy('created_at', 'desc')->paginate(10);
        
        return view('paroisse.retraits', compact('retraits'));
    }
}
