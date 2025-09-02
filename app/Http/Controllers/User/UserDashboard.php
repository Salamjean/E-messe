<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Controller
{
public function dashboard()
{
    $user = Auth::user();
    
    $pendingMesses = $user->messess()
        ->where('statut', 'en attente')
        ->count();
        
    $confirmedMesses = $user->messess()
        ->where('statut', 'confirmee')
        ->count();
        
    $celebratedMesses = $user->messess()
        ->whereIn('statut', ['celebre', 'annulee'])
        ->count();
        
    $totalMesses = $user->messess()->where('statut','!=', 'en_attente_paiement')->count();
    
    $upcomingMesses = $user->messess()
        ->where('statut', '!=', 'annulee')
        ->where('statut','!=', 'en_attente_paiement')
        ->where('date_souhaitee', '>=', now())
        ->orderBy('date_souhaitee')
        ->take(10) // Prendre plus de messes pour le calendrier
        ->get();
        
    return view('user.dashboard', compact(
        'pendingMesses',
        'confirmedMesses',
        'celebratedMesses',
        'totalMesses',
        'upcomingMesses'
    ));
}

    public function logout(Request $request)
    {
        // Mettre à jour le statut actif à 0 avant la déconnexion
        if (Auth::check()) {
            $user = Auth::user();
            $user->actif = 0;
            $user->save();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
