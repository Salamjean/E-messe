<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use App\Models\User;
use Illuminate\Http\Request;
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

        //Nombres d'utilisateurs connectés
        $connectedUsersCount = User::where('actif', 1)->count();
        
        // Données pour le graphique des offrandes (30 derniers jours)
        $offrandesData = [];
        $offrandesLabels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $total = Paroisse::whereDate('created_at', $date)->sum('montant_offrande');
            
            $offrandesData[] = $total;
            $offrandesLabels[] = now()->subDays($i)->format('d M');
        }
        
        // Répartition des paroisses par localisation
        $paroissesStats = Paroisse::select('localisation', DB::raw('count(*) as count'))
            ->groupBy('localisation')
            ->orderBy('count', 'desc')
            ->get();
        
        $paroissesStatsLabels = $paroissesStats->pluck('localisation');
        $paroissesStatsData = $paroissesStats->pluck('count');
        
        // Utilisateurs récents (5 derniers)
        $recentUsers = User::orderBy('created_at', 'desc')->take(3)->get();
        
        // Paroisses récentes (5 dernières)
        $recentParoisses = Paroisse::orderBy('created_at', 'desc')->take(3)->get();
        
        return view('admin.dashboard', compact(
            'usersCount',
            'paroissesCount',
            'totalOffrandes',
            'paroissesStats',
            'offrandesData',
            'offrandesLabels',
            'paroissesStatsLabels',
            'paroissesStatsData',
            'recentUsers',
            'recentParoisses',
            'connectedUsersCount'
        ));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
