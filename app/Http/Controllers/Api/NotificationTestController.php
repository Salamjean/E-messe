<?php

// app/Http/Controllers/Api/NotificationTestController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TestNotification; // Importez la classe de notification de test

class NotificationTestController extends Controller
{
    /**
     * Envoie une notification de test à l'utilisateur connecté.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestNotification(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }

        try {
            // Déclenchement de la notification
            $user->notify(new TestNotification());

            // Vérification si l'utilisateur a un token FCM (le push ne fonctionnera que s'il est là)
            $pushStatus = $user->fcm_token ? 'tenté de s\'envoyer.' : 'non envoyé (token FCM manquant).';

            return response()->json([
                'status' => 'success',
                'message' => 'Notification de test déclenchée pour l\'utilisateur ' . $user->email . '.',
                'details' => "La notification a été enregistrée en base de données et le push FCM a été {$pushStatus}",
                'fcm_token_present' => (bool)$user->fcm_token,
            ], 200);

        } catch (\Exception $e) {
            // En cas d'erreur lors de l'envoi (par exemple, problème de configuration FCM)
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'envoi de la notification de test.',
                'error_detail' => $e->getMessage(),
            ], 500);
        }
    }
}
