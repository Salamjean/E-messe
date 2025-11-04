<?php

namespace App\Http\Controllers\Api\Divers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Verset;

class VersetController extends Controller
{
    /**
     * Retourne le verset du jour pour chaque utilisateur
     */
    public function verset_du_jour()
    {
        $user = Auth::user();
        $aujourdhui = Carbon::today();

        // Vérifie si un verset a déjà été donné aujourd'hui
        $versetJour = DB::table('user_verset_journalier')
            ->where('user_id', $user->id)
            ->where('date', $aujourdhui)
            ->first();

        if ($versetJour) {
            $verset = Verset::find($versetJour->verset_id);
        } else {
            // Récupère les versets déjà vus par l'utilisateur
            $versetsUtilises = DB::table('user_verset_journalier')
                ->where('user_id', $user->id)
                ->pluck('verset_id');

            // Sélection aléatoire d'un nouveau verset
            $verset = Verset::whereNotIn('id', $versetsUtilises)
                ->inRandomOrder()
                ->first();

            // Si tous les versets ont été vus, recommence le cycle
            if (!$verset) {
                $verset = Verset::inRandomOrder()->first();
            }

            // Enregistre le verset du jour pour l'utilisateur
            DB::table('user_verset_journalier')->insert([
                'user_id' => $user->id,
                'verset_id' => $verset->id,
                'date' => $aujourdhui,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'theme' => $verset->theme,
            'reference' => $verset->reference,
            'texte' => $verset->texte,
        ]);
    }
}
