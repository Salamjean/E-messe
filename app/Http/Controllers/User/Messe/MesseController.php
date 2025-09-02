<?php

namespace App\Http\Controllers\User\Messe;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Models\Paiement;
use App\Models\Paroisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MesseController extends Controller
{
    public function index()
    {
        $messess = Auth::user()->messess()
                    ->orderBy('created_at', 'desc')
                    ->where('statut','!=','annulee')
                    ->where('statut','!=','celebre')
                    ->where('statut','!=','en_attente_paiement')
                    ->get();
        return view('user.messe.index', compact('messess'));
    }

    public function history()
    {
        $messess = Auth::user()->messess()
                    ->orderBy('created_at', 'desc')
                    ->where('statut','!=','en attente')
                    ->where('statut','!=','confirmee')
                    ->where('statut','!=','en_attente_paiement')
                    ->get();
        return view('user.messe.history',compact('messess'));
    }
    
    public function create(){
        $paroisses = Paroisse::all();
        return view('user.messe.create', compact('paroisses'));
    }

    public function store(Request $request)
    {
        // Validation des données
       $validator = Validator::make($request->all(), [
            'type_intention' => 'required|in:Defunt,Action graces,Intention particuliere',
            'nom_defunt' => 'required_if:type_intention,Defunt|nullable|string|max:255',
            'motif_action_graces' => 'required_if:type_intention,Action graces|nullable|string|max:255',
            'motif_intention' => 'required_if:type_intention,Intention particuliere|nullable|string|max:255',
            'nom_prenom_concernes' => 'required|array|min:1',
            'nom_prenom_concernes.*' => 'required|string|max:255',
            'celebration_choisie' => 'required|in:Messe quotidienne,Messe dominicale,Messe solennelle',
            'jours_quotidienne' => 'required_if:celebration_choisie,Messe quotidienne|array',
            'jours_dominicale' => 'required_if:celebration_choisie,Messe dominicale|array',
            'montant_offrande' => 'required|numeric|min:0',
            'date_souhaitee' => 'required|date|after:today',
            'heure_souhaitee' => 'nullable|date_format:H:i',
            'paroisse_id' => 'nullable|exists:paroisses,id',
            'nom_demandeur' => 'required|string|max:255',
            'email_demandeur' => 'required|email|max:255',
            'telephone_demandeur' => 'required|string|max:20',
        ], [
            'type_intention.required' => 'Le type d\'intention est obligatoire.',
            'nom_defunt.required_if' => 'Le nom du défunt est obligatoire pour une intention de type défunt.',
            'motif_action_graces.required_if' => 'Le motif est obligatoire pour une action de grâces.',
            'motif_intention.required_if' => 'Le motif est obligatoire pour une intention particulière.',
            'nom_prenom_concernes.required' => 'Les noms et prénoms des concernés sont obligatoires.',
            'date_souhaitee.required' => 'La date souhaitée est obligatoire.',
            'montant_offrande.required' => 'Le montant d\'offrance est obligatoire.',
            'date_souhaitee.after' => 'La date doit être ultérieure à aujourd\'hui.',
            'nom_demandeur.required' => 'Le nom du demandeur est obligatoire.',
            'email_demandeur.required' => 'L\'email du demandeur est obligatoire.',
            'email_demandeur.email' => 'L\'email doit être une adresse valide.',
            'telephone_demandeur.required' => 'Le téléphone du demandeur est obligatoire.',
            'jours_quotidienne.required_if' => 'Veuillez sélectionner au moins un jour pour la messe quotidienne.',
            'jours_dominicale.required_if' => 'Veuillez sélectionner au moins un dimanche pour la messe dominicale.',
            'celebration_choisie.required' => 'Le type de célébration est obligatoire.',
        ]);

       if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Convertir le tableau de noms en JSON
            $nomsConcernes = json_encode($request->nom_prenom_concernes);
            
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
            } 
            elseif ($request->celebration_choisie === 'Messe dominicale') {
                $datesSelectionnees = $request->jours_dominicale ?? [];
            }
            
            // Convertir les dates en JSON pour stockage
            $datesJson = !empty($datesSelectionnees) ? json_encode($datesSelectionnees) : null;
            
            // Création de la messe avec statut "en attente de paiement"
            $messe = Messe::create([
                'user_id' => Auth::user()->id,
                'paroisse_id' => $request->paroisse_id,
                'type_intention' => $request->type_intention,
                'nom_defunt' => $request->nom_defunt,
                'motif_action_graces' => $request->motif_action_graces,
                'motif_intention' => $request->motif_intention,
                'nom_prenom_concernes' => $nomsConcernes,
                'date_souhaitee' => $request->date_souhaitee,
                'heure_souhaitee' => $request->heure_souhaitee,
                'celebration_choisie' => $request->celebration_choisie,
                'nom_demandeur' => $request->nom_demandeur,
                'email_demandeur' => $request->email_demandeur,
                'montant_offrande' => $request->montant_offrande,
                'telephone_demandeur' => $request->telephone_demandeur,
                'statut' => 'en_attente_paiement', // Nouveau statut
                'dates_selectionnees' => $datesJson,
            ]);

            // Générer une référence unique pour le paiement
            $reference = 'MESSE_' . time() . '_' . $messe->id;
            
            // Créer un enregistrement de paiement
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id' => Auth::user()->id,
                'reference' => $reference,
                'montant' => $request->montant_offrande  * 1.01,
                'devise' => 'XOF',
                'methode' => 'wave',
                'statut' => 'en_attente',
            ]);

            // Rediriger vers la page de paiement
            return redirect()->route('user.messe.paiement', ['reference' => $reference])
                ->with('success', 'Votre demande de messe a été enregistrée. Veuillez procéder au paiement.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur s\'est produite lors de l\'enregistrement: ' . $e->getMessage())
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

    
}
