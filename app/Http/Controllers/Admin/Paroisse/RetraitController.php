<?php

namespace App\Http\Controllers\Admin\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use Illuminate\Http\Request;

class RetraitController extends Controller
{
    public function request()
    {
        // Récupérer les retraits en attente
        $retraits = ParoisseRetrait::with('paroisse')->orderBy('created_at', 'desc')
            ->where('statut', 'en_attente')
            ->paginate(10);

        // Statistiques
        $stats = [
            'total_attente' => ParoisseRetrait::where('statut', 'en_attente')->count(),
            'montant_attente' => ParoisseRetrait::where('statut', 'en_attente')->sum('montant'),
            'total_traite' => ParoisseRetrait::whereIn('statut', ['traite', 'complete'])->count(),
            'total_rejete' => ParoisseRetrait::where('statut', 'rejete')->count(),
        ];

        return view('admin.retrait.index', compact('retraits', 'stats'));
    }

    public function history()
    {
        // Récupérer les retraits déjà traités
        $retraits = ParoisseRetrait::with('paroisse')->orderBy('created_at', 'desc')
            ->where('statut', '!=', 'en_attente')
            ->paginate(10);

        // Statistiques
        $stats = [
            'total_virement' => ParoisseRetrait::where('methode', 'virement_bancaire')->whereIn('statut', ['traite', 'complete'])->count(),
            'total_mobile' => ParoisseRetrait::whereIn('methode', ['orange_money', 'mtn_money', 'wave'])->whereIn('statut', ['traite', 'complete'])->count(),
            'montant_total_paye' => ParoisseRetrait::whereIn('statut', ['traite', 'complete'])->sum('montant'),
            'total_historique' => ParoisseRetrait::where('statut', '!=', 'en_attente')->count(),
        ];

        return view('admin.retrait.history', compact('retraits', 'stats'));
    }

    public function confirmer(Request $request, $id)
    {
        $retrait = ParoisseRetrait::findOrFail($id);

        // Vérifier si le retrait est déjà traité
        if ($retrait->statut !== 'en_attente') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande a déjà été traitée',
            ]);
        }

        // Traiter l'upload de preuve pour virement bancaire
        if ($retrait->methode === 'virement_bancaire' && $request->hasFile('preuve_virement')) {
            $file = $request->file('preuve_virement');
            $filename = 'preuve_virement_'.$retrait->reference.'_'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('preuves_virement', $filename, 'public');

            // Sauvegarder le chemin de la preuve
            $retrait->preuve_virement = $path;
        }

        // Mettre à jour le statut
        $retrait->statut = 'traite';
        $retrait->traite_le = now();
        $retrait->save();

        return response()->json([
            'success' => true,
            'message' => 'Retrait confirmé avec succès',
        ]);
    }

    public function rejeter(Request $request, $id)
    {
        $retrait = ParoisseRetrait::findOrFail($id);

        // Vérifier si le retrait est déjà traité
        if ($retrait->statut !== 'en_attente') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande a déjà été traitée',
            ]);
        }

        // Mettre à jour le statut
        $retrait->statut = 'rejete';
        $retrait->raison_rejet = $request->raison;
        $retrait->traite_le = now();
        $retrait->save();

        return response()->json([
            'success' => true,
            'message' => 'Demande rejetée avec succès',
        ]);
    }
}
