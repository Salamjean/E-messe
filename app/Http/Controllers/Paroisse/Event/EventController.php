<?php

namespace App\Http\Controllers\Paroisse\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\NouveauEvenementParoisseNotification;
use Illuminate\Support\Facades\DB;


class EventController extends Controller
{
    public function index()
    {
        $types = Event::distinct()->pluck('type_event');

        return view('paroisse.event.index', compact('types'));
    }

    public function data()
    {
        $events = Event::select(['id', 'titre', 'type_event', 'date_debut', 'date_fin', 'lieu', 'celebrant', 'statut'])
            ->orderByRaw("
                CASE
                    WHEN statut = 'En cours' THEN 1
                    WHEN statut = 'Prévu' THEN 2
                    WHEN statut = 'Terminé' THEN 3
                    ELSE 4
                END ASC
            ")
            ->orderBy('date_debut', 'asc');

        return DataTables::of($events)
            ->editColumn('date_debut', function ($event) {
                return $event->date_debut
                    ? Carbon::parse($event->date_debut)->format('d/m/Y H:i')
                    : 'N/A';
            })
            ->editColumn('date_fin', function ($event) {
                return $event->date_fin
                    ? Carbon::parse($event->date_fin)->format('d/m/Y H:i')
                    : 'N/A';
            })
            ->addColumn('actions', function($event){
                return '
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-warning editBtn" data-id="'.$event->id.'" title="Modifier">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="'.$event->id.'" title="Supprimer">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>';
            })
            ->rawColumns(['actions', 'statut'])
            ->make(true);
    }

    public function show(Event $event)
    {
        return response()->json($event);
    }
    
    private function validateEvent(Request $request)
    {
        return $request->validate([
            'titre' => 'required|string|max:255',
            'type_event' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'nullable|string|max:255',
            'celebrant' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'participation_frais' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'titre.required' => 'Le titre est obligatoire',
            'type_event.required' => 'Le type d\'événement est obligatoire',
            'date_debut.required' => 'La date de début est obligatoire',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début',
            'image.image' => 'Le fichier doit être une image',
            'image.max' => 'L\'image ne doit pas dépasser 2MB',
        ]);
    }

    public function store(Request $request)
    {
        $paroisse = Auth::guard('paroisse')->user();

        try {
            // 1️⃣ Valider les données
            $validatedData = $this->validateEvent($request);

            // 2️⃣ Commencer une transaction pour rollback automatique si erreur
            DB::beginTransaction();

            // 3️⃣ Gérer l'image si présente
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('events_images', 'public');
            }

            $validatedData['statut'] = 'Prévu';
            $validatedData['created_by'] = $paroisse->id;

            // 4️⃣ Créer l'événement
            $event = Event::create($validatedData);

            // 5️⃣ Notifications : récupérer les utilisateurs qui ont cette paroisse comme favori
            $usersToNotify = User::whereHas('favoris', function($query) use ($paroisse) {
                $query->where('paroisse_id', $paroisse->id);
            })->get();

            if ($usersToNotify->isNotEmpty()) {
                Notification::send($usersToNotify, new NouveauEvenementParoisseNotification($event));
                \Log::info('Notification envoyée aux users: ' . $usersToNotify->pluck('id')->implode(', '));
            } else {
                \Log::info('Aucun utilisateur à notifier pour la paroisse ID: ' . $paroisse->id);
            }

            // 6️⃣ Commit de la transaction
            DB::commit();

            // 7️⃣ Redirection avec message de succès
            return redirect()->back()->with('success', 'Événement ajouté avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback si erreur de validation
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Rollback pour toute autre erreur
            DB::rollBack();
            \Log::error('Erreur création événement: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'événement : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Méthode pour valider les données de l'événement
     */





    public function update(Request $request, Event $event)
    {
        try {
            $validatedData = $this->validateEvent($request);

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($event->image && Storage::disk('public')->exists($event->image)) {
                    Storage::disk('public')->delete($event->image);
                }
                $path = $request->file('image')->store('events_images', 'public');
                $validatedData['image'] = $path;
            }

            $event->update($validatedData);

            return response()->json([
                'message' => 'Événement mis à jour avec succès !'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour événement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'événement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Event $event)
    {
        try {
            // Supprimer l'image associée si elle existe
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            
            $event->delete();
            
            return response()->json([
                'message' => 'Événement supprimé avec succès !'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression événement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'événement: ' . $e->getMessage()
            ], 500);
        }
    }

public function showFromNotification($evenement_id)
{
    $event = Event::with('paroisse')->find($evenement_id);

    // if (!$event) {
    //     return response()->json(['message' => 'Événement introuvable.'], 404);
    // }

    return response()->json([
        'id' => $event->id,
        'titre' => $event->titre,
        'type_event' => $event->type_event,
        'description' => $event->description,
        'date_debut' => $event->date_debut,
        'date_fin' => $event->date_fin,
        'lieu' => $event->lieu,
        'celebrant' => $event->celebrant,
        'organisateur' => $event->organisateur,
        'participation_frais' => $event->participation_frais,
        'statut' => $event->statut,
        'image' => $event->image ? asset('storage/'.$event->image) : null,
        'paroisse' => $event->paroisse ? [
            'id' => $event->paroisse->id,
            'nom' => $event->paroisse->nom,
        ] : null,
    ]);
}

}