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
    public function index()
    {
        $today = now()->startOfDay();

        $filteredMessess = Auth::guard('paroisse')->user()->messes()
            ->where('statut', 'confirmee')
            ->whereDate('date_souhaitee', '<=', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paroisse.demande.index', compact('filteredMessess'));
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

    // public function cancel($id)
    // {
    //     // Récupérer la messe avec l'ID
    //     $messe = Messe::findOrFail($id);

    //     // Vérifier que l'utilisateur peut annuler cette messe
    //     if ($messe->paroisse_id !== Auth::guard('paroisse')->user()->id) {
    //         return redirect()->back()->with('error', 'Non autorisé');
    //     }

    //     // Vérifier que la messe peut être annulée
    //     if ($messe->statut !== 'en attente') {
    //         return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être annulées');
    //     }

    //     $messe->update(['statut' => 'annulee']);

    //     return redirect()->route('demandes.messes.index')
    //         ->with('success', 'Demande annulée avec succès');
    // }

    public function validate()
    {
        $filteredMessess = Auth::guard('paroisse')->user()->messes()
            ->orderBy('created_at', 'desc')
            ->where('statut', 'en attente')
            ->get();

        // Filtrer les demandes pour n'afficher que celles avec des dates valides
        // à partir de date_souhaitee
        // $filteredMessess = $messess->filter(function($messe) {
        //     return $messe->hasValidDates();
        // });

        return view('paroisse.demande.validate', compact('filteredMessess'));
    }

    // /NOuvelles fonctions pour les notification

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
