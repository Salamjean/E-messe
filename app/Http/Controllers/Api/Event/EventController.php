<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseNotificationService;

/**
 * @OA\Tag(
 *     name="Événements",
 *     description="Endpoints pour la gestion et l'affichage des événements disponibles"
 * )
 */
class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="Récupérer la liste des événements disponibles pour l'utilisateur connecté",
     *     description="Retourne tous les événements actifs avec leurs détails principaux et la paroisse qui a créé l'événement.",
     *     operationId="getAllEvents",
     *     tags={"Événements"},
     *     security={{"sanctum": {}}}
     * )
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $events = Event::with('paroisse')
            ->whereIn('statut', ['Prévu', 'En cours'])
            ->orderBy('date_debut', 'asc')
            ->get();

        $events = $events->map(function ($event) {
            $event->image_url = $event->image
                ? asset('storage/' . $event->image)
                : asset('images/default_event.png');

            $event->paroisse = $event->paroisse ? [
                'id' => $event->paroisse->id,
                'nom' => $event->paroisse->nom,
                'email' => $event->paroisse->email,
                'telephone' => $event->paroisse->telephone,
            ] : null;

            return $event;
        });

        return response()->json([
            'message' => 'Liste des événements récupérée avec succès',
            'data' => $events
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Afficher les détails d’un événement spécifique",
     *     description="Retourne toutes les informations détaillées sur un événement en fonction de son ID.",
     *     operationId="getEventById",
     *     tags={"Événements"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant unique de l'événement",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'événement récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="titre", type="string", example="Concert de louange"),
     *             @OA\Property(property="description", type="string", example="Une soirée de prière et de chants de louange."),
     *             @OA\Property(property="date_debut", type="string", format="date-time", example="2025-12-05T18:00:00"),
     *             @OA\Property(property="date_fin", type="string", format="date-time", example="2025-12-05T21:00:00"),
     *             @OA\Property(property="lieu", type="string", example="Cathédrale Saint-Paul"),
     *             @OA\Property(property="celebrant", type="string", example="Père Jean Kouassi"),
     *             @OA\Property(property="participation_frais", type="number", example=2000),
     *             @OA\Property(property="image_url", type="string", example="https://tonsite.com/storage/events_images/photo1.jpg"),
     *             @OA\Property(property="paroisse", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Paroisse Saint-Paul"),
     *                 @OA\Property(property="email", type="string", example="paroisse@exemple.com"),
     *                 @OA\Property(property="telephone", type="string", example="+2250700000000")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Événement non trouvé"),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
     * )
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $event = Event::with('paroisse')->find($id);

        if (!$event) {
            return response()->json(['message' => 'Événement non trouvé'], 404);
        }

        $event->image_url = $event->image
            ? asset('storage/' . $event->image)
            : asset('images/default_event.png');

        $event->paroisse = $event->paroisse ? [
            'id' => $event->paroisse->id,
            'nom' => $event->paroisse->nom,
            'email' => $event->paroisse->email,
            'telephone' => $event->paroisse->telephone,
        ] : null;

        return response()->json([
            'message' => 'Détails de l\'événement récupérés avec succès',
            'data' => $event
        ], 200);
    }

    
    public function event_name()
    {
       $typesEvents = Event::select('type_event')
                    ->distinct()
                    ->get();
       
        return response()->json([
            'status' => 'success',
            'data' => $typesEvents,
        ]);
    }


public function showFromNotification($evenement_id)
{
    $event = Event::with('paroisse')->find($evenement_id);



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
            'nom' => $event->paroisse->name,
        ] : null,
    ]);
}
}
