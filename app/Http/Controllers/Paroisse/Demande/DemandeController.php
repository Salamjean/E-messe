<?php

namespace App\Http\Controllers\Paroisse\Demande;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Notifications\MesseCelebreeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PDF;

class DemandeController extends Controller
{
    // public function index()
    // {
    //     $today = now()->startOfDay();

    //     $filteredMessess = Auth::guard('paroisse')->user()->messes()
    //         ->where('statut', 'confirmee')
    //         ->whereDate('date_souhaitee', '<=', $today)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('paroisse.demande.index', compact('filteredMessess'));
    // }

    public function index(Request $request)
    {
        // Si c'est une requête AJAX (venant du DataTable)
        if ($request->ajax()) {
            $today = now()->startOfDay();

            $messes = Auth::guard('paroisse')->user()->messes()
                ->where('statut', 'confirmee')
                ->whereDate('date_souhaitee', '<=', $today)
                ->orderBy('created_at', 'desc')
                ->get();

            // On formate les données pour le DataTable
            $data = $messes->map(function ($messe) {

                // Calculer la progression
                $celebrationCount = $messe->getCelebrationsCount();
                $progressPercentage = $celebrationCount['total'] > 0
                    ? ($celebrationCount['celebrated'] / $celebrationCount['total']) * 100
                    : 0;

                // Formater les noms
                $noms = is_array($messe->nom_prenom_concernes)
                    ? $messe->nom_prenom_concernes
                    : json_decode($messe->nom_prenom_concernes, true) ?? [$messe->nom_prenom_concernes];
                $nomsHtml = collect($noms)->map(function ($nom) {
                    return '<span class="badge bg-light text-dark border me-1">'.$nom.'</span>';
                })->implode(' ');

                // Déterminer le type d'intention
                $typeLabel = match ($messe->type_intention) {
                    'Defunt' => 'Défunt',
                    'Action graces' => 'Action de Grâces',
                    default => 'Intention Particulière',
                };

                return [
                    'id' => $messe->id,
                    'checkbox' => '<input type="checkbox" name="selected_messes[]" value="'.$messe->id.'" class="messe-checkbox form-check-input">',
                    'date_creation' => $messe->created_at->format('d/m/Y'),
                    'date_souhaitee' => Carbon::parse($messe->date_souhaitee)->format('d/m/Y').' '.$messe->heure_souhaitee,
                    'statut' => '<span class="badge badge-'.str_replace(' ', '_', $messe->statut).'">'.ucfirst($messe->statut).'</span>',
                    'type_celebration' => $messe->celebration_choisie,
                    'noms' => $messe->user->name,
                    'progression' => '
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: '.$progressPercentage.'%; background-color: #5ea7b5;"></div>
                        </div>
                        <span class="ms-2" style="font-size:0.8rem">'.$celebrationCount['celebrated'].'/'.$celebrationCount['total'].'</span>
                    </div>',
                    'montant' => number_format($messe->montant_offrande, 0, ',', ' ').' FCFA',
                    'actions' => '
                    <div class="btn-group btn-group-sm">
                        <a href="'.route('paroisse.messe_show', ['messe' => $messe->id]).'" class="btn btn-outline-primary" title="Voir détails">
                            👁️
                        </a>
                        '.($messe->statut === 'en attente' ? '
                        <form action="'.route('paroisse.messe.cancel', ['messe' => $messe->id]).'" method="POST" class="d-inline" onsubmit="return confirm(\'Êtes-vous sûr ?\')">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-outline-danger" title="Annuler">🗑️</button>
                        </form>' : '').'
                    </div>',
                ];
            });

            return response()->json(['data' => $data]);
        }

        // Si chargement normal de la page
        return view('paroisse.demande.index');
    }

    // Méthode pour vérifier si toutes les dates ont été célébrées
    private function allDatesCelebrated($messe)
    {
        // Vérifier si toutes les dates dans dates_selectionnees sont passées
        $dates = json_decode($messe->dates_selectionnees, true);

        if (! is_array($dates) || empty($dates)) {
            // Pour les messes sans dates multiples, vérifier si date_souhaitee est passée
            return Carbon::parse($messe->date_souhaitee)->endOfDay()->isPast();
        }

        // Pour les messes avec dates multiples, vérifier si toutes les dates sont passées
        foreach ($dates as $date) {
            if (! $this->isDatePast($date, $messe->date_souhaitee)) {
                return false;
            }
        }

        return true;
    }

    // Méthode pour vérifier si une date est passée
    private function isDatePast($date, $startDate)
    {
        $start = Carbon::parse($startDate);

        // Si c'est un jour de la semaine
        if (in_array($date, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'])) {
            $nextDate = $this->findNextDayOccurrence($date, $start);

            return $nextDate->endOfDay()->isPast();
        }

        // Si c'est une date spécifique
        try {
            $dateObj = Carbon::parse($date);

            return $dateObj->endOfDay()->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    // Méthode pour trouver la prochaine occurrence d'un jour
    private function findNextDayOccurrence($day, $startDate)
    {
        $frenchDays = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4,
            'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];

        $targetDay = $frenchDays[$day];
        $currentDay = $startDate->dayOfWeekIso;

        $daysToAdd = $targetDay - $currentDay;
        if ($daysToAdd < 0) {
            $daysToAdd += 7;
        }

        return $startDate->copy()->addDays($daysToAdd);
    }

    public function show($id)
    {
        // Récupérer la messe avec l'ID
        $messe = Messe::findOrFail($id);

        // Vérifier que l'utilisateur peut voir cette messe
        if ($messe->paroisse_id !== Auth::guard('paroisse')->user()->id) {
            abort(403, 'Accès non autorisé');
        }

        return view('paroisse.demande.show', compact('messe'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required',
        ]);

        $selectedIds = $request->selected_ids;
        if (is_string($selectedIds)) {
            $selectedIds = json_decode($selectedIds, true);
        }

        if (! is_array($selectedIds) || empty($selectedIds)) {
            return redirect()->back()->with('error', 'Aucune demande sélectionnée.');
        }

        foreach ($selectedIds as $id) {
            $messe = Messe::find($id);
            if (! $messe) {
                continue;
            }

            // Incrémentation
            $messe->download_count = ($messe->download_count ?? 0) + 1;
            $messe->last_downloaded_at = now();

            // Vérifier le nombre de célébrations
            $celebrationCount = $messe->getCelebrationsCount();

            if ($messe->download_count >= $celebrationCount['total']) {
                $messe->statut = 'celebre';

                // --- Envoi de la notification ---
                if ($messe->user) {
                    try {
                        // Assurez-vous d'avoir créé la classe MesseCelebreeNotification
                        $messe->user->notify(new \App\Notifications\MesseCelebreeNotification($messe));
                    } catch (\Exception $e) {
                        Log::error('Échec de l\'envoi de la notif (célébrée) pour la messe #'.$messe->id.': '.$e->getMessage());
                    }
                }

                Log::info('Statut changé à "célébrée" pour la messe ID: '.$id.
                    ', Téléchargements: '.$messe->download_count.
                    ', Total prévu: '.$celebrationCount['total']);
            }

            $messe->save();
        }

        // Récupérer les messes à inclure dans le PDF
        $selectedMessess = Messe::whereIn('id', $selectedIds)
            ->with('paroisse')
            ->orderBy('date_souhaitee')
            ->get();

        if ($selectedMessess->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune demande valide sélectionnée.');
        }

        // Données pour le PDF
        $data = [
            'messess' => $selectedMessess,
            'date_export' => now()->format('d/m/Y à H:i'),
            'total' => $selectedMessess->count(),
            'paroisse' => Auth::guard('paroisse')->user(),
        ];

        // Génération du PDF
        $pdf = PDF::loadView('paroisse.demande.pdf-template', $data);
        $filename = 'demandes-messe-'.now()->format('Y-m-d-H-i').'.pdf';

        Log::info('PDF généré avec succès: '.$filename);

        // ✅ Téléchargement
        return $pdf->download($filename);
    }

    // Ajoutez cette nouvelle méthode pour gérer la mise à jour du statut
    public function updateStatusToCelebrated(Request $request)
    {
        Log::info('Début de updateStatusToCelebrated');
        Log::info('Données reçues: ', $request->all());

        try {
            // Récupérer les IDs directement depuis la requête
            $selectedIds = $request->selected_ids;

            // Si c'est une chaîne JSON, la décoder
            if (is_string($selectedIds)) {
                $selectedIds = json_decode($selectedIds, true);
            }

            // Vérifier que nous avons bien un tableau
            if (! is_array($selectedIds)) {
                Log::error('Format des IDs invalide: '.gettype($selectedIds));

                return response()->json(['success' => false, 'error' => 'Format des IDs invalide'], 400);
            }

            Log::info('IDs sélectionnés: ', $selectedIds);

            foreach ($selectedIds as $id) {
                $messe = Messe::find($id);

                if (! $messe) {
                    Log::warning('Messe non trouvée avec ID: '.$id);

                    continue;
                }

                Log::info('Traitement de la messe ID: '.$id);

                // Vérifier si toutes les dates ont été célébrées
                $celebrationCount = $messe->getCelebrationsCount();
                Log::info('Compteur de célébration: ', $celebrationCount);

                if ($celebrationCount['celebrated'] >= $celebrationCount['total']) {
                    $messe->statut = 'celebre';
                    $messe->save();
                    Log::info('Statut mis à jour pour la messe ID: '.$id);
                } else {
                    Log::info('La messe ID: '.$id.' n\'a pas encore toutes ses célébrations');
                }
            }

            Log::info('Fin de updateStatusToCelebrated - Succès');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erreur dans updateStatusToCelebrated: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // public function validate()
    // {
    //     $filteredMessess = Auth::guard('paroisse')->user()->messes()
    //         ->orderBy('created_at', 'desc')
    //         ->where('statut', 'en attente')
    //         ->get();

    //     return view('paroisse.demande.validate', compact('filteredMessess'));
    // }

    public function validate(Request $request)
    {
        if ($request->ajax()) {
            $messes = Auth::guard('paroisse')->user()->messes()
                ->where('statut', 'en attente')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'data' => $messes->map(function ($messe) {

                    // --- MISE A JOUR ICI : On pointe vers 'show_details' ---
                    // Assurez-vous que cette route existe dans web.php
                    $urlShow = route('paroisse.messe.show_details', ['messe' => $messe->id]);

                    $urlConfirm = route('paroisse.messe.confirmed', ['messe' => $messe->id]);
                    $urlCancel = route('paroisse.messe.cancel', ['messe' => $messe->id]);

                    // Nom à afficher dans le tableau
                    $nomsAffiche = $messe->user->name ?? 'Anonyme';

                    return [
                        'id' => $messe->id,
                        'checkbox' => '<input type="checkbox" class="messe-checkbox form-check-input" value="'.$messe->id.'">',
                        'date_creation' => $messe->created_at->format('d/m/Y'),
                        'type_intention' => $messe->celebration_choisie,
                        'noms' => $nomsAffiche,
                        'date_souhaitee' => Carbon::parse($messe->date_souhaitee)->format('d/m/Y'),
                        'heure' => $messe->heure_souhaitee ?? '-',
                        'offrande' => number_format($messe->montant_offrande, 0, ',', ' ').' FCFA',
                        'motif_intention' => $messe->motif_intention,

                        'actions' => '
                    <div class="d-flex justify-content-center gap-1">
                        <!-- Bouton VOIR avec la classe "btn-show-details" -->
                        <button type="button" class="btn btn-sm btn-info text-white btn-show-details" 
                                style="background-color: #d9d9d9; border:none;" 
                                data-url="'.$urlShow.'" 
                                title="Voir les détails">
                            👁️
                        </button>
                        
                        <button type="button" class="btn btn-sm btn-success confirm-single-btn"
                                style="background-color: #c49d54; border:none;" 
                                data-url="'.$urlConfirm.'" 
                                title="Confirmer">
                            ✅
                        </button>
                            
                        <button type="button" class="btn btn-sm btn-danger cancel-single-btn" 
                                data-url="'.$urlCancel.'" 
                                style="background-color: #de353e; border:none;"
                                title="Annuler">
                            ❌
                        </button>
                    </div>
                ',
                    ];
                }),
            ]);
        }

        return view('paroisse.demande.validate');
    }

    // Votre fonction renommée show_details
    public function show_details($id)
    {
        // Récupération sécurisée
        $messe = Auth::guard('paroisse')->user()->messes()->with('user')->findOrFail($id);

        // Traitement pour l'affichage propre des "Noms concernés"
        $noms_concernes = $messe->nom_prenom_concernes;

        // Si c'est stocké en JSON ou Array, on le convertit en string lisible
        if (is_string($noms_concernes)) {
            $decoded = json_decode($noms_concernes, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $noms_concernes = implode(', ', $decoded);
            }
        } elseif (is_array($noms_concernes)) {
            $noms_concernes = implode(', ', $noms_concernes);
        }

        // Retourne les données formatées pour le JS
        return response()->json([
            'type_intention' => $messe->celebration_choisie,
            'date_souhaitee' => Carbon::parse($messe->date_souhaitee)->format('d/m/Y'),
            'heure' => $messe->heure_souhaitee ?? 'Non spécifiée',
            'offrande' => number_format($messe->montant_offrande, 0, ',', ' '),
            'noms' => $noms_concernes,
            'demandeur' => $messe->user ? $messe->user->name : 'Anonyme',
            'status' => $messe->status ?? 'En attente',
            'motif_intention' => $messe->motif_intention,
        ]);
    }

    public function celebrated(Request $request)
    {
        if ($request->ajax()) {
            $messes = Auth::guard('paroisse')
                ->user()
                ->messes()
                ->where('statut', 'confirmee')
                ->where('date_souhaitee', '>=', now())
                ->orderBy('date_souhaitee', 'desc')
                ->get();

            return response()->json([
                'data' => $messes->map(function ($messe) {
                    // Gestion des noms concernés (pour l'intention)
                    // On suppose que c'est stocké dans 'nom_prenom_concernes' ou similaire
                    // Si vous n'avez pas ce champ, remplacez par $messe->motif_intention
                    $rawNoms = $messe->nom_prenom_concernes ?? [];

                    $noms = is_array($rawNoms)
                        ? $rawNoms
                        : (json_decode($rawNoms, true) ?? [$rawNoms]);

                    // Si vide ou null, on met un tableau vide
                    if (! is_array($noms)) {
                        $noms = [];
                    }

                    return [
                        'id' => $messe->id,
                        'checkbox' => '<input type="checkbox" class="messe-checkbox form-check-input" value="'.$messe->id.'">',
                        'date_souhaitee' => $messe->date_souhaitee ? Carbon::parse($messe->date_souhaitee)->format('d/m/Y') : '-',
                        'heure_souhaitee' => $messe->heure_souhaitee ?? '-',
                        'intention' => $messe->celebration_choisie,
                        'nom_concerne' => $messe->user->name,
                        'offrande' => number_format($messe->montant_offrande, 0, ',', ' ').' FCFA',
                        'statut' => $messe->statut,
                        // Données brutes pour la modale
                        'full_details' => [
                            'demandeur' => $messe->user->name,
                            'telephone' => $messe->telephone_demandeur ?? 'Non renseigné',
                            'email' => $messe->email_demandeur ?? 'Non renseigné',
                            'motif' => $messe->motif_intention ?? 'Aucun motif spécifié',
                            'celebration' => $messe->celebration_choisie ?? 'Non spécifié',
                            'noms_text' => implode(', ', $noms),
                        ],
                    ];
                }),
            ]);
        }

        return view('paroisse.demande.hold_celebration');
    }

    public function cancel($id)
    {
        // Récupérer la messe avec l'ID
        $messe = Messe::findOrFail($id);

        // Vérifier que l'utilisateur peut annuler cette messe
        if ($messe->paroisse_id !== Auth::guard('paroisse')->user()->id) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        // Vérifier que la messe peut être annulée
        // Modifié pour permettre l'annulation des messes confirmées mais pas encore célébrées
        if (! in_array($messe->statut, ['en attente', 'confirmee'])) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus être annulée.');
        }

        $messe->update(['statut' => 'annulee']);

        // --- Envoi de la notification via le système Laravel ---

        if ($messe->user) {
            try {
                // Notification en base + FCM automatique
                $messe->user->notify(new \App\Notifications\MesseAnnuleeNotification($messe));
            } catch (\Exception $e) {
                \Log::error("Échec notification messe #{$messe->id}: ".$e->getMessage());
            }
        }

        return redirect()->route('demandes.messes.index')
            ->with('success', 'Demande annulée avec succès.');
    }

    // Confirmer une seule messe
    public function confirmed($id)
    {
        $messe = Messe::findOrFail($id);

        if ($messe->paroisse_id !== Auth::guard('paroisse')->user()->id) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        if ($messe->statut !== 'en attente') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être confirmées.');
        }

        $messe->update(['statut' => 'confirmee']);

        if ($messe->user) {
            try {
                // Notification en base + FCM automatique
                $messe->user->notify(new \App\Notifications\MesseConfirmeeNotification($messe));
            } catch (\Exception $e) {
                \Log::error("Échec notification messe #{$messe->id}: ".$e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Demande confirmée avec succès.');
    }

    // Confirmer plusieurs messes
    public function bulkConfirm(Request $request)
    {
        try {
            $selectedIds = $request->selected_ids;

            if (is_string($selectedIds)) {
                $selectedIds = json_decode($selectedIds, true);
            }

            if (! is_array($selectedIds) || empty($selectedIds)) {
                return redirect()->back()->with('error', 'Aucune demande sélectionnée.');
            }

            $messesToUpdate = Messe::whereIn('id', $selectedIds)
                ->where('paroisse_id', Auth::guard('paroisse')->user()->id)
                ->where('statut', 'en attente')
                ->get();

            // Mettre à jour le statut
            Messe::whereIn('id', $messesToUpdate->pluck('id'))->update(['statut' => 'confirmee']);

            foreach ($messesToUpdate as $messe) {
                if ($messe->user) {
                    try {
                        $messe->user->notify(new \App\Notifications\MesseConfirmeeNotification($messe));
                    } catch (\Exception $e) {
                        \Log::error("Échec notification groupée messe #{$messe->id}: ".$e->getMessage());
                    }
                }
            }

            return redirect()->back()->with('success', count($messesToUpdate).' demande(s) confirmée(s) avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur confirmation groupée: '.$e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la confirmation.');
        }
    }

    public function bulkCancel(Request $request)
    {
        try {
            $selectedIds = $request->selected_ids;

            if (is_string($selectedIds)) {
                $selectedIds = json_decode($selectedIds, true);
            }

            if (! is_array($selectedIds) || empty($selectedIds)) {
                return redirect()->back()->with('error', 'Aucune demande sélectionnée.');
            }

            // On charge les relations 'user' et 'paroisse'
            $messesToUpdate = Messe::with(['user', 'paroisse'])
                ->whereIn('id', $selectedIds)
                ->where('paroisse_id', Auth::guard('paroisse')->user()->id)
                // On peut annuler les messes 'en attente' ou 'confirmee'
                ->whereIn('statut', ['en attente', 'confirmee'])
                ->get();

            if ($messesToUpdate->isEmpty()) {
                return redirect()->back()->with('error', 'Aucune demande valide à annuler.');
            }

            // Mettre à jour le statut
            Messe::whereIn('id', $messesToUpdate->pluck('id'))->update(['statut' => 'annulee']);

            // --- Envoi des notifications en boucle ---
            foreach ($messesToUpdate as $messe) {
                if ($messe->user) {
                    try {
                        $messe->user->notify(new \App\Notifications\MesseConfMesseAnnuleeNotificationirmeeNotification($messe));
                    } catch (\Exception $e) {
                        Log::error("Échec de l'envoi de la notif groupée (annul.) pour la messe #{$messe->id}: ".$e->getMessage());
                    }
                }
            }

            return redirect()->back()->with('success', count($messesToUpdate).' demande(s) annulée(s) avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation groupée: '.$e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'annulation.');
        }
    }
}
