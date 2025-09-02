<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ->where('montant_offrande', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['montant_offrande', 'created_at', 'type_intention', 'nom_demandeur']);
        
        // Récupérer les offrandes des 6 derniers mois
        $monthlyOffrandes = $paroisse->messess()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(montant_offrande) as total')
            ->whereNotNull('montant_offrande')
            ->where('montant_offrande', '>', 0)
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Préparer les données pour le graphique
        $monthlyOffrandeData = [];
        $monthlyOffrandeLabels = [];
        
        // Générer les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $monthName = $date->locale('fr')->shortMonthName;
            
            $monthlyOffrandeLabels[] = ucfirst($monthName) . ' ' . $year;
            
            // Chercher si des données existent pour ce mois
            $monthData = $monthlyOffrandes->first(function ($item) use ($year, $month) {
                return $item->year == $year && $item->month == $month;
            });
            
            $monthlyOffrandeData[] = $monthData ? $monthData->total : 0;
        }
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
        $soldeDisponible = ($totalPaiements - $totalRetraits) / 1.01 ;
        
        // Récupérer les retraits récents
        $derniersRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Calculer les paiements des 6 derniers mois pour le graphique
        $monthlyPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->selectRaw('YEAR(paiements.created_at) as year, MONTH(paiements.created_at) as month, SUM(paiements.montant) as total')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->where('paiements.created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Préparer les données pour le graphique des paiements
        $monthlyPaiementData = [];
        $monthlyPaiementLabels = [];
        
        // Générer les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $monthName = $date->locale('fr')->shortMonthName;
            
            $monthlyPaiementLabels[] = ucfirst($monthName) . ' ' . $year;
            
            // Chercher si des données existent pour ce mois
            $monthData = $monthlyPaiements->first(function ($item) use ($year, $month) {
                return $item->year == $year && $item->month == $month;
            });
            
            $monthlyPaiementData[] = $monthData ? $monthData->total : 0;
        }
        
        return view('paroisse.dashboard', compact(
            'pendingDemandes',
            'confirmedDemandes',
            'celebratedDemandes',
            'totalDemandes',
            'totalOffrandes',
            'upcomingMessess',
            'latestOffrandes',
            'monthlyOffrandeData',
            'monthlyOffrandeLabels',
            'totalPaiements', // Nouvelle variable
            'monthlyPaiementData', // Nouvelle variable
            'monthlyPaiementLabels', // Nouvelle variable
            'soldeDisponible', // Nouvelle variable
            'derniersRetraits' // Nouvelle variable
        ));
    }

    public function logout(){
        Auth::guard('paroisse')->logout();
        return redirect()->route('paroisse.login');
    }
}
