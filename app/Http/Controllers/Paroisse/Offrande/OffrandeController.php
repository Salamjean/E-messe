<?php

namespace App\Http\Controllers\Paroisse\Offrande;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffrandeController extends Controller
{
    public function create(){
        $paroisse = Paroisse::find(Auth::guard('paroisse')->user()->id);
        return view('paroisse.offrande.create', compact('paroisse'));
    }

    public function storeOffrande(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        try {
            // Récupérer la paroisse (vous devrez peut-être adapter cette partie)
            $paroisse = Paroisse::find(Auth::guard('paroisse')->user()->id); // ou autre logique
            
            // Ajouter le montant
            $paroisse->montant_offrande = $request->montant;
            $paroisse->save();

            return response()->json([
                'success' => true,
                'message' => 'Offrande ajoutée avec succès!',
                'new_amount' => $paroisse->montant_offrande
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }
    
    public function history(){
        $messess = Auth::guard('paroisse')->user()->messess()
                    ->where('statut','!=','en attente')
                    ->where('statut','!=','confirmee')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        // Filtrer les demandes pour n'afficher que celles avec des dates valides
        // à partir de date_souhaitee
        $filteredMessess = $messess->filter(function($messe) {
            return $messe->hasValidDates();
        });
        return view('paroisse.offrande.history', compact('filteredMessess'));
    }
}
