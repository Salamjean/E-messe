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

// ---------------------- PROFILE ----------------------
public function profile(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Utilisateur non connecté.'
        ], 401);
    }

    // URL publique de la photo
    $profileUrl = $user->profile_picture ? asset('storage/' . $user->profile_picture) : null;

    return response()->json([
        'status' => 'success',
        'user' => [
            'id'             => $user->id,
            'name'           => $user->name,
            'user_name'      => $user->user_name,
            'email'          => $user->email,
            'contact'        => $user->contact,
            'civilite'       => $user->civilite,
            'indicatif'      => $user->indicatif,
            'commune'        => $user->commune,
            'CMU'            => $user->CMU,
            'profile_picture'=> $profileUrl
        ]
    ]);
}


// ---------------------- UPDATE PROFILE ----------------------
public function updateProfile(Request $request)
{
    $user = $request->user();

    $validatedData = $request->validate([
        'name'            => 'sometimes|string|max:255',
        'user_name'       => 'sometimes|string|max:191|unique:users,user_name,' . $user->id,
        'email'           => 'sometimes|email|max:191|unique:users,email,' . $user->id,
        'contact'         => 'sometimes|string|max:20|unique:users,contact,' . $user->id,
        'civilite'        => 'sometimes|string|max:10',
        'indicatif'       => 'sometimes|string|max:10',
        'commune'         => 'sometimes|string|max:255',
        'CMU'             => 'sometimes|nullable|string|max:255',
        'profile_picture' => 'nullable',
    ]);

    $newPhoto = $request->profile_picture;
    $oldPhoto = $user->profile_picture;

    /** ───────────────────────────────
     * 1️⃣ SI NOUVELLE PHOTO
     * ───────────────────────────────
     */
    if ($newPhoto) {

        // 🔥 SUPPRESSION de l'ancienne photo
        if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        /** 📌 CAS 1 — FICHIER UPLOADED */
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        /** 📌 CAS 2 — BASE64 */
        elseif (str_starts_with($newPhoto, 'data:image')) {
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $newPhoto);
            $image = base64_decode($image);

            // Extension
            preg_match('/^data:image\/(\w+);base64/', $newPhoto, $matches);
            $ext = $matches[1] ?? 'png';

            $fileName = 'profiles/' . uniqid('pp_', true) . '.' . $ext;
            Storage::disk('public')->put($fileName, $image);

            $user->profile_picture = $fileName;
        }
    }

    /** ───────────────────────────────
     * 2️⃣ UPDATE DES AUTRES CHAMPS
     * ───────────────────────────────
     */
    $user->fill(collect($validatedData)->except('profile_picture')->all());
    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Profil mis à jour avec succès.',
        'user' => $this->formatUser($user),
    ]);
}



// ---------------------- UTILITAIRE ----------------------
private function formatUser($user)
{
    return [
        'id'             => $user->id,
        'name'           => $user->name,
        'user_name'      => $user->user_name,
        'email'          => $user->email,
        'contact'        => $user->contact,
        'civilite'       => $user->civilite,
        'indicatif'      => $user->indicatif,
        'commune'        => $user->commune,
        'CMU'            => $user->CMU,
        'profile_picture'=> $user->profile_picture ? asset('storage/' . $user->profile_picture) : null
    ];
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

    
// public function updateProfile(Request $request)
// {
//     $user = $request->user();

//     if (!$user) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'Utilisateur non connecté.'
//         ], 401);
//     }

//     // Validation simple pour les champs texte + profile_picture
//     $validatedData = $request->validate([
//         'name'            => 'sometimes|string|max:255',
//         'user_name'       => 'sometimes|string|max:191|unique:users,user_name,' . $user->id,
//         'email'           => 'sometimes|email|max:191|unique:users,email,' . $user->id,
//         'contact'         => 'sometimes|string|max:20|unique:users,contact,' . $user->id,
//         'civilite'        => 'sometimes|string|max:10',
//         'indicatif'       => 'sometimes|string|max:10',
//         'commune'         => 'sometimes|string|max:255',
//         'CMU'             => 'sometimes|nullable|string|max:255',
//         'profile_picture' => 'nullable', // on gère le fichier séparément
//     ]);

//     // --- Gestion de la photo ---
//     $profilePath = $user->profile_picture;

//     if ($request->has('profile_picture')) {
//         // Supprimer l'ancienne photo si elle existe
//         if ($profilePath && \Storage::disk('public')->exists($profilePath)) {
//             \Storage::disk('public')->delete($profilePath);
//         }

//         // Fichier uploadé multipart
//         if ($request->hasFile('profile_picture')) {
//             $profilePath = $request->file('profile_picture')->store('profiles', 'public');
//         }
//         // Image base64
//         elseif (is_string($validatedData['profile_picture']) && str_starts_with($validatedData['profile_picture'], 'data:image')) {
//             $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $validatedData['profile_picture']));
//             preg_match('/^data:image\/(\w+);base64,/', $validatedData['profile_picture'], $matches);
//             $extension = $matches[1] ?? 'png';
//             $fileName = 'profiles/' . uniqid('user_' . $user->id . '_', true) . '.' . $extension;
//             \Storage::disk('public')->put($fileName, $fileData);
//             $profilePath = $fileName;
//         }
//         // Si vide ou null => suppression
//         else {
//             $profilePath = null;
//         }
//     }

//     $user->fill(collect($validatedData)->except('profile_picture')->all());
//     $user->profile_picture = $profilePath;
//     $user->save();

//     // URL publique de la photo
//     $profileUrl = $profilePath ? asset('storage/' . $profilePath) : null;

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Profil mis à jour avec succès.',
//         'user' => [
//             'id'             => $user->id,
//             'name'           => $user->name,
//             'user_name'      => $user->user_name,
//             'email'          => $user->email,
//             'contact'        => $user->contact,
//             'civilite'       => $user->civilite,
//             'indicatif'      => $user->indicatif,
//             'commune'        => $user->commune,
//             'CMU'            => $user->CMU,
//             'profile_picture'=> $profileUrl
//         ]
//     ]);
// }





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
