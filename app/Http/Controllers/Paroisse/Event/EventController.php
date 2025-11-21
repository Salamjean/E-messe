<?php

namespace App\Http\Controllers\Paroisse\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\NouveauEvenementParoisseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FcmService;

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
            ->editColumn('date_debut', fn($event) => $event->date_debut ? Carbon::parse($event->date_debut)->format('d/m/Y H:i') : 'N/A')
            ->editColumn('date_fin', fn($event) => $event->date_fin ? Carbon::parse($event->date_fin)->format('d/m/Y H:i') : 'N/A')
            ->addColumn('actions', fn($event) => '
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-warning editBtn" data-id="'.$event->id.'" title="Modifier">
                        <i class="material-icons">edit</i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="'.$event->id.'" title="Supprimer">
                        <i class="material-icons">delete</i>
                    </button>
                </div>'
            )
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
            // Validation
            $validatedData = $this->validateEvent($request); // Assure-toi que cette méthode existe

            DB::beginTransaction();

            // Gestion de l'image
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('events_images', 'public');
            }

            $validatedData['statut'] = 'Prévu';
            $validatedData['created_by'] = $paroisse->id;

            // Création de l'événement
            $event = Event::create($validatedData);

            // Récupération des utilisateurs à notifier (ceux qui ont la paroisse en favori + un token)
            $usersToNotify = User::whereHas('favoris', function($query) use ($paroisse) {
                $query->where('paroisse_id', $paroisse->id);
            })->whereNotNull('fcm_token')->get();

            if ($usersToNotify->isNotEmpty()) {
                // ✅ CORRECT : On utilise Notification::send
                // Cela va déclencher via() -> toArray() (Database) ET toFcmHttp() (FCM)
                Notification::send($usersToNotify, new NouveauEvenementParoisseNotification($event));
                
                Log::info('Notification "Nouvel Event" envoyée à ' . $usersToNotify->count() . ' utilisateurs.');
            } else {
                Log::info('Aucun utilisateur avec token FCM trouvé pour cette paroisse.');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Événement ajouté avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création événement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }   

    public function sendFcm($notifiable, Event $event)
    {
        if (empty($notifiable->fcm_token)) return null;

        $paroisseName  = $event->paroisse->name ?? 'la paroisse';
        $dateFormatted = $event->date_debut->format('d/m/Y');

        $title = 'Nouvel événement paroissial 🎊';
        $body  = "{$paroisseName} organise l'événement « {$event->titre} » le {$dateFormatted}.";

        return (new FcmService())->send($notifiable->fcm_token, $title, $body, [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'type'         => 'nouveau_evenement',
            'evenement_id' => (string) $event->id,
            'paroisse_id'  => (string) $event->created_by,
            'paroisse_name'=> $paroisseName,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        try {
            $validatedData = $this->validateEvent($request);

            if ($request->hasFile('image')) {
                if ($event->image && Storage::disk('public')->exists($event->image)) {
                    Storage::disk('public')->delete($event->image);
                }
                $validatedData['image'] = $request->file('image')->store('events_images', 'public');
            }

            $event->update($validatedData);

            return response()->json(['message' => 'Événement mis à jour avec succès !']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour événement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'événement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Event $event)
    {
        try {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }

            $event->delete();

            return response()->json(['message' => 'Événement supprimé avec succès !']);

        } catch (\Exception $e) {
            Log::error('Erreur suppression événement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'événement: ' . $e->getMessage()
            ], 500);
        }
    }
}
