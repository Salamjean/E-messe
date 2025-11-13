<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Messe;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return response()->json([
            'notifications' => $user->notifications
        ]);
    }

        // 2. Lister uniquement les notifications non lues
    public function unread()
    {
        $user = Auth::user();
        return response()->json([
            'notifications' => $user->unreadNotifications
        ]);
    }

    // 4. Marquer TOUTES les notifications comme lues
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Toutes les notifications ont été marquées comme lues']);
    }
    
    // 5. Supprimer une notification
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification introuvable'], 404);
        }
        
        $notification->delete();
        return response()->json(['message' => 'Notification supprimée']);
    }

    // 6. Supprimer TOUTES les notifications
    public function clearAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();
        
        return response()->json(['message' => 'Toutes les notifications ont été supprimées']);
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification introuvable'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marquée comme lue']);
    }


    public function showMesseDetails($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification introuvable'], 404);
        }

        // Marquer la notification comme lue
        $notification->markAsRead();

        // Récupérer l'ID de la messe depuis la colonne dédiée ou le champ data
        $messeId = $notification->messe_id ?? $notification->data['messe_id'] ?? null;


        if (!$messeId) {
            return response()->json(['message' => 'Aucun ID de messe associé à cette notification.'], 404);
        }

        $messe = Messe::with('paroisse.commune')->find($messeId);

        if (!$messe) {
            return response()->json(['message' => 'Messe introuvable'], 404);
        }

        // Vous pouvez ajouter des informations supplémentaires si nécessaire
        // Par exemple, le statut de la messe, les dates valides, etc.
        $messe->valid_dates = $messe->getValidDates();
        $messe->celebrations_count = $messe->getCelebrationsCount();


        return response()->json($messe);
    }
}
