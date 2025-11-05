<?php

namespace App\Http\Controllers\Paroisse\Offrande;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        ]);

        try {
            // Récupérer l'utilisateur paroisse connecté
            $user = Auth::guard('paroisse')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            // Mettre à jour le montant
            $user->montant_offrande = $request->montant;
            // dd($user);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Montant de demande de messe mis à jour avec succès!',
                'new_amount' => $user->montant_offrande
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur storeOffrande: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour'
            ], 500);
        }
    }
    
    public function history(){
        $messess = Auth::guard('paroisse')->user()->messes()
                    ->where('statut','!=','en attente')
                    ->where('statut','!=','confirmee')
                    ->where('statut','!=','en_attente_paiement')
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
