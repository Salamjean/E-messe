<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Reversement;
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
        $totalDemandes = $paroisse->messes()->whereIn('statut', ['en attente', 'confirmee', 'celebre'])->count();

        // Montant total des demandes (Somme des offrandes uniquement)
        $totalOffrandes = $paroisse->messes()
            ->whereIn('statut', ['en attente', 'confirmee', 'celebre'])
            ->sum('montant_offrande');

        $montantDemande = $paroisse->montant_offrande;
        // --- 2. LOGIQUE PORTEFEUILLE ---

        // Somme des offrandes pour les paiements reçus (statut 'paye')
        $totalPaiementsOffrande = DB::table('messes')
            ->join('paiements', 'messes.id', '=', 'paiements.messe_id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('messes.montant_offrande');

        // Somme des retraits effectués (hors rejetés) via paroisse_retraits
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        // Somme des reversements via API qui sont encore en attente (car ils ne sont pas encore dans paroisse_retraits)
        $totalReversementsApiPending = Reversement::where('paroisse_id', $paroisse->id)
            ->where('statut', 'pending')
            ->sum('montant');

        // Calcul du solde disponible (Formule : Recettes Offrandes - Retraits - Reversals en attente)
        $soldeDisponible = (int) $totalPaiementsOffrande - (int) ($totalRetraits + $totalReversementsApiPending);

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
        $types = Event::where('paroisse_id', $paroisse->id)->distinct()->pluck('type_event');

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
            'types',
            'montantDemande'
        ));
    }

    public function logout()
    {
        Auth::guard('paroisse')->logout();

        return redirect()->route('paroisse.login');
    }
}
