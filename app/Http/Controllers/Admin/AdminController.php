<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistiques de base
        $usersCount = User::count();
        $paroissesCount = Paroisse::count();
        $totalOffrandes = Paroisse::sum('montant_offrande');
        $connectedUsersCount = User::where('actif', 1)->count();

        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        // Données pour le graphique des offrandes (30 derniers jours) - INCHANGÉ
        $offrandesData = [];
        $offrandesLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $total = Paroisse::whereDate('created_at', $date)->sum('montant_offrande');
            $offrandesData[] = $total;
            $offrandesLabels[] = now()->subDays($i)->format('d M');
        }

        // =====================================================================
        // ---- CORRECTION : Répartition des paroisses par commune ----
        // =====================================================================
        $paroissesStats = Paroisse::with('commune') // On pré-charge la relation avec la commune
            ->select('commune_id', DB::raw('count(*) as count'))
            ->groupBy('commune_id')
            ->orderBy('count', 'desc')
            ->get();

        // On utilise les relations pour créer des labels plus clairs (ex: "Cocody")
        $paroissesStatsLabels = $paroissesStats->map(function ($stat) {
            return $stat->commune ? $stat->commune->nom_commune : 'Non définie';
        });
        $paroissesStatsData = $paroissesStats->pluck('count');

        // Utilisateurs récents (3 derniers)
        $recentUsers = User::orderBy('created_at', 'desc')->take(3)->get();

        // Paroisses récentes (3 dernières) - ON AJOUTE la relation commune.ville
        $recentParoisses = Paroisse::with('commune.ville')->orderBy('created_at', 'desc')->take(3)->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'paroissesCount',
            'totalOffrandes',
            'connectedUsersCount',
            'offrandesData',
            'offrandesLabels',
            'paroissesStatsLabels',
            'paroissesStatsData',
            'recentUsers',
            'recentParoisses',
            'pendingWithdrawalsCount'
        ));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }
}
