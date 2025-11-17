<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Récupérer le profil de l'utilisateur connecté
     */

        /**
     * @OA\Get(
     *     path="/api/user/profile",
     *     summary="Profil utilisateur",
     *     description="Récupère les informations du profil de l'utilisateur connecté",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Profil récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="user_name", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="indicatif", type="string", example="+225"),
     *                 @OA\Property(property="contact", type="string", example="01234567"),
     *                 @OA\Property(property="commune", type="string", example="Abobo"),
     *                 @OA\Property(property="CMU", type="string", nullable=true, example="CMU12345"),
     *                 @OA\Property(property="profile_picture", type="string", nullable=true),
     *                 @OA\Property(property="actif", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */

    public function profile(Request $request)
    {

        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }

    /**
     * Mettre à jour le profil
     */

        /**
     * @OA\Put(
     *     path="/api/user/profile",
     *     summary="Mettre à jour le profil",
     *     description="Met à jour les informations du profil utilisateur",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=191, example="John Doe Updated"),
     *             @OA\Property(property="user_name", type="string", maxLength=191, example="johndoe_updated"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=191, example="john.updated@example.com"),
     *             @OA\Property(property="indicatif", type="string", maxLength=10, example="+225"),
     *             @OA\Property(property="contact", type="string", maxLength=191, example="07654321"),
     *             @OA\Property(property="commune", type="string", maxLength=191, example="Cocody"),
     *             @OA\Property(property="CMU", type="string", maxLength=191, nullable=true, example="CMU67890"),
     *             @OA\Property(property="profile_picture", type="string", maxLength=191, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */

    
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non connecté.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name'            => 'sometimes|string|max:255',
            'indicatif'       => 'sometimes|string|max:10',
            'contact'         => 'sometimes|string|max:20',
            'commune'         => 'sometimes|string|max:255',
            'CMU'             => 'sometimes|string|max:255|nullable',
            'profile_picture' => [
                'nullable',
                // Accepte une chaîne (pour base64 ou suppression) ou un fichier.
                function ($attribute, $value, $fail) {
                    if (is_string($value)) {
                        // Permet une chaîne vide pour la suppression
                        if ($value === '') {
                            return;
                        }
                        // Valide si c'est une chaîne base64
                        if (!preg_match('/^data:image\/(\w+);base64,/', $value)) {
                            $fail('Le champ ' . $attribute . ' doit être une image valide au format base64.');
                        }
                    } else {
                        // Valide si c'est un fichier image
                        $validator = Validator::make(['profile_picture' => $value], [
                            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                        ]);
                        if ($validator->fails()) {
                            $fail('Le fichier de l\'attribut ' . $attribute . ' n\'est pas une image valide.');
                        }
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $updateData = collect($validatedData)->except('profile_picture')->all();

        // Gestion de la photo de profil
        if ($request->has('profile_picture')) {
            $profilePicture = $request->input('profile_picture');
            $newImagePath = null;

            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Si un nouveau fichier est uploadé (multipart)
            if ($request->hasFile('profile_picture')) {
                $newImagePath = $request->file('profile_picture')->store('profiles', 'public');
            }
            // Si une image en base64 est fournie
            elseif (is_string($profilePicture) && str_starts_with($profilePicture, 'data:image')) {
                // Extrait les données de l'image
                list($type, $data) = explode(';', $profilePicture);
                list(, $data)      = explode(',', $data);
                $imageData = base64_decode($data);

                // Crée un nom de fichier unique
                $extension = explode('/', explode(':', $type)[1])[1];
                $fileName = 'profiles/' . uniqid() . '.' . $extension;

                // Sauvegarde l'image
                Storage::disk('public')->put($fileName, $imageData);
                $newImagePath = $fileName;
            }

            $updateData['profile_picture'] = $newImagePath;
        }

        $user->update($updateData);

        // Rafraîchir le modèle pour obtenir les dernières données
        $user->refresh();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil mis à jour avec succès.',
            'user' => $user
        ]);
    }


    /**
     * Changer le mot de passe
     */


        /**
     * @OA\Post(
     *     path="/api/user/change-password",
     *     summary="Changer le mot de passe",
     *     description="Change le mot de passe de l'utilisateur connecté",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","password","password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password", example="oldpassword123"),
     *             @OA\Property(property="password", type="string", format="password", minLength=6, example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe changé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Mot de passe changé")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Mot de passe actuel incorrect",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Le mot de passe actuel est incorrect.")
     *         )
     *     )
     * )
     */

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Le mot de passe actuel est incorrect.'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Mot de passe changé'
        ]);
    }

    /**
     * Mettre à jour les préférences de notifications
     */


        /**
     * @OA\Put(
     *     path="/api/user/notifications",
     *     summary="Préférences de notifications",
     *     description="Met à jour les préférences de notifications de l'utilisateur",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"notifications"},
     *             @OA\Property(property="notifications", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Préférences mises à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Préférences de notifications mises à jour"),
     *             @OA\Property(property="notifications", type="boolean", example=true)
     *         )
     *     )
     * )
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notifications' => 'required|boolean'
        ]);

        $user = $request->user();
        $user->update([
            'notifications' => $request->notifications
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Préférences de notifications mises à jour',
            'notifications' => $user->notifications
        ]);
    }

        /**
     * @OA\Patch(
     *     path="/api/users/{id}/pref/toggle",
     *     tags={"Utilisateurs"},
     *     summary="Basculer la préférence d'un utilisateur",
     *     description="Inverse la valeur du champ 'pref' (true <-> false) pour un utilisateur.",
     *     operationId="toggleUserPref",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Préférence basculée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Préférence basculée"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jean Dupont"),
     *                 @OA\Property(property="email", type="string", example="jean@example.com"),
     *                 @OA\Property(property="pref", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Utilisateur non trouvé")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function togglePref($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Basculer la valeur
        $user->pref = !$user->pref;
        $user->save();

        return response()->json([
            'message' => 'Préférence basculée',
            'user' => $user
        ]);
    }

}
