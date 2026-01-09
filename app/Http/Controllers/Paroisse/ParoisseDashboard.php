<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParoisseDashboard extends Controller
{
    public function dashboard()
    {
        $paroisse = Auth::guard('paroisse')->user();

        // --- 1. CARTES STATISTIQUES (CARDS) ---

        // Compteurs par statut (Pour le graphe rond et les cartes)
        $pendingDemandes = $paroisse->messes()->where('statut', 'en attente')->count();
        $confirmedDemandes = $paroisse->messes()->where('statut', 'confirmee')->count();
        $celebratedDemandes = $paroisse->messes()->where('statut', 'celebre')->count();

        // Total global des demandes (pour calculer les pourcentages)
        $totalDemandes = $paroisse->messes()->where('statut', 'confirmee')->count();

        // Montant total des demandes (Somme des offrandes)
        // $totalOffrandes = $paroisse->messes()
        //     ->whereNotNull('montant_offrande')
        //     ->sum('montant_offrande');

        $totalOffrandes = $paroisse->montant_offrande;

        // --- 2. LOGIQUE PORTEFEUILLE ---

        // Somme des paiements reçus (statut 'paye')
        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        // Somme des retraits effectués (hors rejetés)
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        // dd($totalRetraits);

        // Calcul du solde disponible (Formule : Recettes / 1.01 - Retraits)
        $soldeDisponible = ($totalPaiements) - $totalRetraits;

        // --- 3. GRAPHIQUE LINÉAIRE (EVOLUTION MENSUELLE) ---
        // On veut le NOMBRE de demandes par mois (Jan-Déc) pour cette année vs année passée.

        $currentYear = Carbon::now()->year;
        $lastYear = Carbon::now()->subYear()->year;

        // Fonction locale pour récupérer un tableau de 12 entiers (Jan-Déc)
        $getMonthlyCounts = function ($year) use ($paroisse) {
            // Récupère les données brutes : [Mois => Nombre]
            $counts = $paroisse->messes()
                ->where('statut', '!=', 'en_attente_paiement')
                ->selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
                ->whereYear('updated_at', $year)
                ->groupBy('month')
                ->pluck('total', 'month') // Renvoie un tableau associatif [Mois => Total]
                ->toArray();

            // Remplit les mois vides avec 0 pour avoir toujours 12 valeurs
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $data[] = $counts[$i] ?? 0;
            }

            return $data;
        };

        // dd($getMonthlyCounts($currentYear));
        $chartDataCurrentYear = $getMonthlyCounts($currentYear);
        $chartDataLastYear = $getMonthlyCounts($lastYear);

        // --- 4. LISTES (BAS DE PAGE) ---

        // Prochaines messes à célébrer (Liste gauche)
        $upcomingMessess = $paroisse->messes()
            ->whereIn('statut', ['confirmee', 'celebre']) // On affiche confirmées et célébrées futures
            ->where('date_souhaitee', '>=', now())
            ->orderBy('date_souhaitee', 'asc')
            ->take(5)
            ->get();

        // dd($upcomingMessess);
        // Dernières demandes reçues (Carte droite)
        $latestOffrandes = $paroisse->messes()
            ->orderBy('created_at', 'desc')
            ->where('statut', '!=', 'en_attente_paiement')
            ->take(1)
            ->get();

        // dd($latestOffrandes);
        $types = Event::distinct()->pluck('type_event');

        return view('paroisse.dashboard', compact(
            'pendingDemandes',
            'confirmedDemandes',
            'celebratedDemandes',
            'totalDemandes',
            'totalOffrandes',
            'soldeDisponible',
            'upcomingMessess',
            'latestOffrandes',
            'chartDataCurrentYear',
            'chartDataLastYear',
            'types'
        ));
    }

    public function logout()
    {
        Auth::guard('paroisse')->logout();

        return redirect()->route('paroisse.login');
    }
}
