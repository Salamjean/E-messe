<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParoisseDashboard extends Controller
{
    public function dashboard()
    {
        $paroisse = Auth::guard('paroisse')->user();
        
        $pendingDemandes = $paroisse->messess()
            ->where('statut', 'en attente')
            ->count();
            
        $confirmedDemandes = $paroisse->messess()
            ->where('statut', 'confirmee')
            ->count();
            
        $celebratedDemandes = $paroisse->messess()
            ->where('statut', 'celebre')
            ->count();
            
        $totalDemandes = $paroisse->messess()->count();
        
        $totalOffrandes = $paroisse->montant_offrande ?? 0;
        
        $upcomingMessess = $paroisse->messess()
            ->where('statut', 'confirmee')
            ->where('date_souhaitee', '>=', now())
            ->orderBy('date_souhaitee')
            ->take(5)
            ->get();
        
        // Récupérer les dernières offrandes depuis la table messes
        $latestOffrandes = $paroisse->messess()
            ->whereNotNull('montant_offrande')
            ->where('statut', 'en attente')
            ->where('montant_offrande', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['montant_offrande', 'created_at', 'type_intention', 'nom_demandeur']);
            
        return view('paroisse.dashboard', compact(
            'pendingDemandes',
            'confirmedDemandes',
            'celebratedDemandes',
            'totalDemandes',
            'totalOffrandes',
            'upcomingMessess',
            'latestOffrandes'
        ));
    }

    public function logout(){
        Auth::guard('paroisse')->logout();
        return redirect()->route('paroisse.login');
    }
}
