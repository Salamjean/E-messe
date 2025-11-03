<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserNotificationController extends Controller
{
    /**
     * @OA\Patch(
     *     path="/api/users/{id}/notifications/toggle",
     *     tags={"Notifications"},
     *     summary="Toggle des préférences de notifications",
     *     description="Bascule true/false pour les champs cochés dans le body. Les champs non fournis ne changent pas.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="emailNotif", type="boolean", example=true),
     *             @OA\Property(property="smsNotif", type="boolean", example=false),
     *             @OA\Property(property="pushNotif", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notifications basculées",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notifications basculées"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
     *     )
     * )
     */
public function updateAll(Request $request, $id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }

    // Validation des données
    $request->validate([
        'emailNotif' => 'required|boolean',
        'smsNotif' => 'required|boolean',
        'pushNotif' => 'required|boolean',
    ]);

    // Mise à jour directe sans toggle
    $user->emailNotif = $request->emailNotif;
    $user->smsNotif = $request->smsNotif;
    $user->pushNotif = $request->pushNotif;

    $user->save();

    return response()->json([
        'message' => 'Préférences de notifications mises à jour',
        'user' => $user
    ]);
}

}
