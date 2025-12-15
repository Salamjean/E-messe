<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Affiche la liste des événements avec filtres.
     */
    public function index(Request $request)
    {
        // Récupérer tous les types d'événements uniques pour le filtre
        $types = Event::select('type_event')->distinct()->orderBy('type_event')->pluck('type_event');

        // Requête de base pour les événements en cours ou à venir
        $query = Event::query()
            ->with('paroisse') // Eager loading pour éviter le problème N+1
            ->where('statut', '!=', 'Annulé')
            ->where('date_fin', '>=', Carbon::today()) // *** MODIFIÉ : Uniquement les événements en cours ou futurs
            ->orderBy('date_debut', 'asc');

        // Appliquer le filtre par type d'événement
        if ($request->filled('type_event')) {
            $query->where('type_event', $request->input('type_event'));
        }

        // Appliquer le filtre par date
        if ($request->filled('filter_date')) {
            $filterDate = Carbon::parse($request->input('filter_date'))->toDateString();
            
            // Condition : l'événement doit être actif à la date spécifiée.
            $query->where('date_debut', '<=', $filterDate)
                  ->where('date_fin', '>=', $filterDate);
        }

        // Paginer les résultats pour la vue carte
        $events = $query->paginate(6); // *** MODIFIÉ : Pagination à 6

        // Retourner la vue avec les données nécessaires
        return view('user.event.index', [
            'events' => $events,
            'types' => $types,
            'selected_type' => $request->input('type_event'),
            'selected_date' => $request->input('filter_date'),
        ]);
    }
}