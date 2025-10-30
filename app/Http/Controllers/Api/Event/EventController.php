<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des événements récupérée avec succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Concert de louange"),
     *                 @OA\Property(property="date_debut", type="string", format="date-time", example="2025-12-05T18:00:00"),
     *                 @OA\Property(property="date_fin", type="string", format="date-time", example="2025-12-05T21:00:00"),
     *                 @OA\Property(property="lieu", type="string", example="Cathédrale Saint-Paul"),
     *                 @OA\Property(property="celebrant", type="string", example="Père Jean Kouassi"),
     *                 @OA\Property(property="participation_frais", type="number", format="float", example=2000),
     *                 @OA\Property(property="image_url", type="string", example="https://tonsite.com/storage/events_images/photo1.jpg"),
     *                 @OA\Property(property="paroisse", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nom", type="string", example="Paroisse Saint-Paul"),
     *                     @OA\Property(property="email", type="string", example="paroisse@exemple.com"),
     *                     @OA\Property(property="telephone", type="string", example="+2250700000000")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Utilisateur non authentifié")
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

        // Charger les événements avec la paroisse associée
        $events = Event::with('paroisse')
            ->whereIn('statut', ['Prévu', 'En cours'])
            ->orderBy('date_debut', 'asc')
            ->get(['id', 'titre', 'date_debut', 'date_fin', 'lieu', 'celebrant', 'participation_frais', 'image', 'created_by']);

        // Transformer la collection pour inclure l'image complète et les infos paroisse
        $events = $events->map(function ($event) {
            $event->image_url = $event->image
                ? asset('storage/' . $event->image)
                : asset('images/default_event.png');

            // Infos de la paroisse
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
}
