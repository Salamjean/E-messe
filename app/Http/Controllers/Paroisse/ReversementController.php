<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use App\Models\Reversement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReversementController extends Controller
{
    private function calculerSolde($paroisseId)
    {
        $totalRecettesOffrande = DB::table('messes')
            ->join('paiements', 'messes.id', '=', 'paiements.messe_id')
            ->where('messes.paroisse_id', $paroisseId)
            ->where('paiements.statut', 'paye')
            ->sum('messes.montant_offrande');

        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisseId)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        $totalReversementsApiPending = Reversement::where('paroisse_id', $paroisseId)
            ->where('statut', 'pending')
            ->sum('montant');

        return (int) $totalRecettesOffrande - (int) ($totalRetraits + $totalReversementsApiPending);
    }

    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string',
            'prefix' => 'required|string',
            'telephone' => 'required|string',
        ]);

        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        if ($request->montant > $soldeDisponible) {
            return response()->json(['message' => 'Solde insuffisant.'], 422);
        }

        DB::beginTransaction();

        try {
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->telephone);
            $cleanPrefix = preg_replace('/[^0-9]/', '', $request->prefix);

            $reference = 'REV-'.time().'-'.$paroisse->id;

            $retrait = new ParoisseRetrait;
            $retrait->paroisse_id = $paroisse->id;
            $retrait->montant = $request->montant;
            $retrait->methode = $request->methode;
            $retrait->numero_compte = $cleanPrefix.$cleanPhone;
            $retrait->nom_banque = $request->methode;
            $retrait->nom_titulaire = $paroisse->name;
            $retrait->reference = $reference;
            $retrait->statut = 'en_attente';
            $retrait->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de retrait a été enregistrée avec succès. Elle sera traitée par l\'administration.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur demande retrait: '.$e->getMessage());

            return response()->json(['message' => 'Une erreur interne est survenue.'], 500);
        }
    }

    public function notifyCinetPay(Request $request)
    {
        return response()->json(['code' => 200, 'message' => 'Reçu']);
    }
}
