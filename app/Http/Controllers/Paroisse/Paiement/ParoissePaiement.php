<?php

namespace App\Http\Controllers\Paroisse\Paiement;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParoissePaiement extends Controller
{
    public function index()
    {
        $paroisse = Auth::guard('paroisse')->user();
    
        // Récupérer les retraits de la paroisse
        $retraits = ParoisseRetrait::where('paroisse_id', $paroisse->id)
                    ->where('statut','en_attente')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
         $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');
        
        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut','!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');
        
        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits  ;
        
        return view('paroisse.retrait.index', compact('retraits', 'soldeDisponible'));
    }
    
    public function history()
    {
        $paroisse = Auth::guard('paroisse')->user();
    
        // Récupérer les retraits de la paroisse
        $retraits = ParoisseRetrait::where('paroisse_id', $paroisse->id)
                    ->where('statut','!=','en_attente')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
         $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');
        
        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut','!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');
        
        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits  ;
        
        return view('paroisse.retrait.history', compact('retraits', 'soldeDisponible'));
    }

    public function create(){
        $paroisse = Auth::guard('paroisse')->user();
        // Calculer le montant total des paiements pour cette paroisse
        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');
        
        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut','!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');
        
        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits  ;

        return view('paroisse.retrait.create',compact('soldeDisponible'));
    }

    public function requestRetrait(Request $request)
    {
        $rules = [
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string',
            'numero_compte' => 'required|string',
            'nom_titulaire' => 'required|string',
        ];
        
        // Ajouter la règle conditionnelle pour nom_banque
        if ($request->methode === 'virement_bancaire') {
            $rules['nom_banque'] = 'required|string';
        }
        
        $request->validate($rules);
        
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
        $retrait->nom_banque = $request->nom_banque; // Nouveau champ
        $retrait->reference = 'RET' . time() . $paroisse->id;
        $retrait->statut = 'en_attente';
        $retrait->save();
        
        return redirect()->route('paroisse.retraits')->with('success', 'Votre demande de retrait a été envoyée avec succès.');
    }

    public function annuler($id)
    {
        $retrait = ParoisseRetrait::findOrFail($id);
        
        // Vérifier que le retrait appartient à la paroisse connectée
        if ($retrait->paroisse_id !== Auth::guard('paroisse')->id()) {
            return back()->with('error', 'Action non autorisée.');
        }
        
        // Vérifier que le retrait est encore en attente
        if ($retrait->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les retraits en attente peuvent être annulés.');
        }
        
        // Annuler le retrait
        $retrait->statut = 'rejete';
        $retrait->save();
        
        return back()->with('success', 'La demande de retrait a été annulée avec succès.');
    }

}
