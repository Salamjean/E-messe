<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
