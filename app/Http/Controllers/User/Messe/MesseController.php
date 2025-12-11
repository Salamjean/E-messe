<?php

namespace App\Http\Controllers\User\Messe;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Messe;
use App\Models\Paiement;
use App\Models\Paroisse;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDF;

class MesseController extends Controller
{
    public function index()
    {
        $messess = Auth::user()->messes()
            ->orderBy('created_at', 'desc')
            ->where('statut', '!=', 'annulee')
            ->where('statut', '!=', 'celebre')
            ->where('statut', '!=', 'en_attente_paiement')
            ->get();

        $favorites = Auth::user()->favoris()->with(['paroisse' => function($q) {
            $q->with('commune');
        }])->get()->pluck('paroisse');

        return view('user.messe.index', compact('messess', 'favorites'));
    }

    public function history()
    {
        $messess = Auth::user()->messes()
            ->orderBy('created_at', 'desc')
            ->where('statut', '!=', 'en attente')
            ->where('statut', '!=', 'confirmee')
            ->where('statut', '!=', 'en_attente_paiement')
            ->get();

        $favorites = Auth::user()->favoris()->with(['paroisse' => function($q) {
            $q->with('commune');
        }])->get()->pluck('paroisse');

        return view('user.messe.history', compact('messess', 'favorites'));
    }

    public function historique_messes()
    {
        $messess = Auth::user()->messes()
            ->orderBy('created_at', 'desc')
            ->where('statut', '=', 'celebre')
            ->get();

        $favorites = Auth::user()->favoris()->with(['paroisse' => function($q) {
            $q->with('commune');
        }])->get()->pluck('paroisse');

        return view('user.messe.historique_messes', compact('messess', 'favorites'));
    }

    public function hold()
    {
        $messess = Auth::user()->messes()
            ->orderBy('created_at', 'desc')
            ->where('statut', '!=', 'celebre')
            ->get();

        $favorites = Auth::user()->favoris()->with(['paroisse' => function($q) {
            $q->with('commune');
        }])->get()->pluck('paroisse');

        return view('user.messe.hold', compact('messess', 'favorites'));
    }

    public function create()
    {
        $villes = Ville::orderBy('nom_ville')->get();

        // Le reste des données que vous pourriez passer à la vue...
        return view('user.messe.create', compact('villes'));
    }

    // NOUVELLES MÉTHODES POUR AJAX
    public function getCommunes($ville_id)
    {
        $communes = Commune::where('ville_id', $ville_id)->orderBy('nom_commune')->get();

        return response()->json($communes);
    }

    public function getParoisses($commune_id)
    {
        $paroisses = Paroisse::where('commune_id', $commune_id)->orderBy('name')->get(['id', 'name', 'montant_offrande']);

        // dd($paroisses);
        return response()->json($paroisses);
    }

    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'motif_intention' => 'required|string|max:255',
            'interception_par' => 'nullable|string|max:255',
            'celebration_choisie' => 'required|in:Messe quotidienne,Messe dominicale,Messe solennelle',
            'jours_quotidienne' => 'required_if:celebration_choisie,Messe quotidienne|array',
            'jours_dominicale' => 'required_if:celebration_choisie,Messe dominicale|array',
            'montant_offrande' => 'required|numeric|min:0',
            'date_souhaitee' => 'required|date|after:today',
            'heure_souhaitee' => 'nullable|date_format:H:i',
            'paroisse_id' => 'nullable|exists:paroisses,id',
            'ville_id' => 'required|exists:villes,id',
            'commune_id' => 'required|exists:communes,id',
            'nom_demandeur' => 'required|string|max:255',
            'email_demandeur' => 'required|email|max:255',
            'telephone_demandeur' => 'required|string|max:20',
        ], [
            'motif_intention.required' => 'Le motif de la messe est obligatoire.',
            'celebration_choisie.required' => 'Le type de célébration est obligatoire.',
            'jours_quotidienne.required_if' => 'Veuillez sélectionner au moins un jour pour la messe quotidienne.',
            'jours_dominicale.required_if' => 'Veuillez sélectionner au moins un dimanche pour la messe dominicale.',
            'montant_offrande.required' => 'Le montant d\'offrande est obligatoire.',
            'date_souhaitee.required' => 'La date souhaitée est obligatoire.',
            'date_souhaitee.after' => 'La date doit être ultérieure à aujourd\'hui.',
            'paroisse_id.required' => 'La paroisse est obligatoire.',
            'ville_id.required' => 'La ville est obligatoire.',
            'commune_id.required' => 'La commune est obligatoire.',
            'nom_demandeur.required' => 'Le nom du demandeur est obligatoire.',
            'email_demandeur.required' => 'L\'email du demandeur est obligatoire.',
            'email_demandeur.email' => 'L\'email doit être une adresse valide.',
            'telephone_demandeur.required' => 'Le téléphone du demandeur est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Préparer les dates sélectionnées
            $datesSelectionnees = [];

            if ($request->celebration_choisie === 'Messe quotidienne') {
                $jours = $request->jours_quotidienne ?? [];
                $nomsJours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                foreach ($jours as $jour) {
                    $index = intval($jour) - 1;
                    if (isset($nomsJours[$index])) {
                        $datesSelectionnees[] = $nomsJours[$index];
                    }
                }
            } elseif ($request->celebration_choisie === 'Messe dominicale') {
                $datesSelectionnees = $request->jours_dominicale ?? [];
            }

            // Convertir les dates en JSON pour stockage
            $datesJson = ! empty($datesSelectionnees) ? json_encode($datesSelectionnees) : null;

            // Création de la messe avec statut "en attente de paiement"
            $messe = Messe::create([
                'user_id' => Auth::user()->id,
                'paroisse_id' => $request->paroisse_id,
                'interception_par' => $request->interception_par,
                'motif_intention' => $request->motif_intention,
                'date_souhaitee' => $request->date_souhaitee,
                'heure_souhaitee' => $request->heure_souhaitee,
                'celebration_choisie' => $request->celebration_choisie,
                'nom_demandeur' => $request->nom_demandeur,
                'email_demandeur' => $request->email_demandeur,
                'telephone_demandeur' => $request->telephone_demandeur,
                'statut' => 'en_attente_paiement',
                'montant_offrande' => $request->montant_offrande,
                'dates_selectionnees' => $datesJson,
            ]);

            // Générer une référence unique pour le paiement
            $reference = 'MESSE_'.time().'_'.$messe->id;

            // Créer un enregistrement de paiement
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id' => Auth::user()->id,
                'reference' => $reference,
                'montant' => $request->montant_offrande * 1.02,
                'devise' => 'XOF',
                'methode' => 'wave',
                'statut' => 'en_attente',
            ]);

            // Rediriger vers la page de paiement
            return redirect()->route('user.messe.paiement', ['reference' => $reference])
                ->with('success', 'Votre demande de messe a été enregistrée. Veuillez procéder au paiement.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur s\'est produite lors de l\'enregistrement: '.$e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        // Récupérer la messe avec l'ID
        $messe = Messe::findOrFail($id);

        // Vérifier que l'utilisateur peut voir cette messe
        if ($messe->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('user.messe.show', compact('messe'));
    }

    public function destroy($id)
    {
        // Récupérer la messe avec l'ID
        $messe = Messe::findOrFail($id);

        // Vérifier que l'utilisateur peut supprimer cette messe
        if ($messe->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        // Vérifier que la messe peut être supprimée
        if ($messe->statut !== 'en attente') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être supprimées');
        }

        // Supprimer la demande
        $messe->delete();

        return redirect()->route('user.messe.index')
            ->with('success', 'Demande supprimée avec succès');
    }

    public function downloadReceipt(Messe $messe)
    {
        if ($messe->user_id !== Auth::user()->id) {
            abort(403, 'Accès non autorisé');
        }

        $messe->load('paroisse', 'paiements');

        $pdf = PDF::loadView('user.messe.receipt', compact('messe'));

        // Format A4 pour le reçu
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('margin-top', 10);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('margin-right', 10);
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('images', true);

        $filename = 'reçu-messe-'.($messe->paiements->first()->reference ?? 'M'.$messe->id).'.pdf';

        return $pdf->download($filename);
    }
}
