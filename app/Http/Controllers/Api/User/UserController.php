<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\UserDelete;


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

    \Log::info('🚀 === DÉBUT MISE À JOUR PROFIL ===', [
        'user_id' => $user->id,
        'user_name' => $user->user_name,
        'request_data' => $request->except(['profile_picture', 'password']),
        'has_profile_picture' => $request->hasFile('profile_picture')
    ]);

    try {
        \Log::info('📋 Validation des données en cours...');
        
        $validatedData = $request->validate([
            'name'            => 'sometimes|string|max:255',
            'user_name'       => 'sometimes|string|max:191|unique:users,user_name,' . $user->id,
            'email'           => 'sometimes|email|max:191|unique:users,email,' . $user->id,
            'contact'         => 'sometimes|string|max:20',
            'civilite'        => 'sometimes|string|max:10',
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'profile_picture.image' => 'Le fichier doit être une image.',
            'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'profile_picture.max' => 'L\'image ne doit pas dépasser 2048 KB.',
        ]);

        \Log::info('✅ VALIDATION RÉUSSIE', ['champs_validés' => array_keys($validatedData)]);

        // 1. Mettre à jour l'image de profil si fournie
        if ($request->hasFile('profile_picture')) {
            \Log::info('📸 TRAITEMENT PHOTO - Début', [
                'file_name' => $request->file('profile_picture')->getClientOriginalName(),
                'file_size' => $request->file('profile_picture')->getSize(),
                'file_type' => $request->file('profile_picture')->getMimeType()
            ]);

            // Supprimer l'ancienne image si elle existe
            if ($user->profile_picture) {
                if (Storage::disk('public')->exists($user->profile_picture)) {
                    \Log::info('🗑️ SUPPRESSION ANCIENNE PHOTO', ['ancien_chemin' => $user->profile_picture]);
                    Storage::disk('public')->delete($user->profile_picture);
                    \Log::info('✅ ANCIENNE PHOTO SUPPRIMÉE');
                } else {
                    \Log::warning('⚠️ ANCIENNE PHOTO INTROUVABLE', ['chemin' => $user->profile_picture]);
                }
            } else {
                \Log::info('ℹ️ AUCUNE ANCIENNE PHOTO À SUPPRIMER');
            }
            
            \Log::info('💾 ENREGISTREMENT NOUVELLE PHOTO...');
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            \Log::info('✅ NOUVELLE PHOTO ENREGISTRÉE', ['nouveau_chemin' => $profilePicturePath]);
            
            $user->profile_picture = $profilePicturePath;
            \Log::info('💾 SAUVEGARDE AVEC NOUVELLE PHOTO...');
            $user->save();
            \Log::info('✅ SAUVEGARDE PHOTO RÉUSSIE');
        } else {
            \Log::info('📷 AUCUNE NOUVELLE PHOTO FOURNIE');
        }

        // 2. Mettre à jour le nom si fourni
        if (isset($validatedData['name'])) {
            \Log::info('✏️ MISE À JOUR NOM', [
                'ancien' => $user->name,
                'nouveau' => $validatedData['name']
            ]);
            $user->name = $validatedData['name'];
            $user->save();
            \Log::info('✅ NOM MIS À JOUR');
        }

        // 3. Mettre à jour le username si fourni
        if (isset($validatedData['user_name'])) {
            \Log::info('👤 MISE À JOUR USERNAME', [
                'ancien' => $user->user_name,
                'nouveau' => $validatedData['user_name']
            ]);
            $user->user_name = $validatedData['user_name'];
            $user->save();
            \Log::info('✅ USERNAME MIS À JOUR');
        }

        // 4. Mettre à jour l'email si fourni
        if (isset($validatedData['email'])) {
            \Log::info('📧 MISE À JOUR EMAIL', [
                'ancien' => $user->email,
                'nouveau' => $validatedData['email']
            ]);
            $user->email = $validatedData['email'];
            $user->save();
            \Log::info('✅ EMAIL MIS À JOUR');
        }

        // 5. Mettre à jour le contact si fourni
        if (isset($validatedData['contact'])) {
            \Log::info('📞 MISE À JOUR CONTACT', [
                'ancien' => $user->contact,
                'nouveau' => $validatedData['contact']
            ]);
            $user->contact = $validatedData['contact'];
            $user->save();
            \Log::info('✅ CONTACT MIS À JOUR');
        }

        // 6. Mettre à jour la civilité si fournie
        if (isset($validatedData['civilite'])) {
            \Log::info('👔 MISE À JOUR CIVILITÉ', [
                'ancien' => $user->civilite,
                'nouveau' => $validatedData['civilite']
            ]);
            $user->civilite = $validatedData['civilite'];
            $user->save();
            \Log::info('✅ CIVILITÉ MIS À JOUR');
        }

        // 7. Mettre à jour l'indicatif si fourni
        if (isset($validatedData['indicatif'])) {
            \Log::info('🌍 MISE À JOUR INDICATIF', [
                'ancien' => $user->indicatif,
                'nouveau' => $validatedData['indicatif']
            ]);
            $user->indicatif = $validatedData['indicatif'];
            $user->save();
            \Log::info('✅ INDICATIF MIS À JOUR');
        }

        // 8. Mettre à jour la commune si fournie
        if (isset($validatedData['commune'])) {
            \Log::info('🏠 MISE À JOUR COMMUNE', [
                'ancien' => $user->commune,
                'nouveau' => $validatedData['commune']
            ]);
            $user->commune = $validatedData['commune'];
            $user->save();
            \Log::info('✅ COMMUNE MIS À JOUR');
        }

        // 9. Mettre à jour le CMU si fourni
        if (isset($validatedData['CMU'])) {
            \Log::info('🏥 MISE À JOUR CMU', [
                'ancien' => $user->CMU,
                'nouveau' => $validatedData['CMU']
            ]);
            $user->CMU = $validatedData['CMU'];
            $user->save();
            \Log::info('✅ CMU MIS À JOUR');
        }

        \Log::info('🎉 === MISE À JOUR PROFIL TERMINÉE AVEC SUCCÈS ===', [
            'user_id' => $user->id,
            'champs_mis_à_jour' => array_keys($validatedData)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil mis à jour avec succès.',
            'user' => $this->formatUser($user),
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('❌ ERREUR DE VALIDATION', [
            'errors' => $e->errors(),
            'user_id' => $user->id,
            'request_data' => $request->except(['profile_picture', 'password'])
        ]);
        
        // Relancer l'exception pour que Laravel la gère normalement
        throw $e;

    } catch (\Exception $e) {
        \Log::error('💥 ERREUR CRITIQUE DANS updateProfile', [
            'user_id' => $user->id,
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'stack_trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.',
            'debug' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
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


    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mot de passe incorrect.'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mot de passe vérifié avec succès.'
        ]);
    }


public function deleteAccount(Request $request)
{
    // Validation du mot de passe
    $request->validate([
        'password' => 'required|string'
    ]);

    $user = $request->user();

    if (!$user) {
        Log::error("Utilisateur non authentifié lors de deleteAccount");
        return response()->json([
            'status' => 'error',
            'message' => 'Utilisateur non authentifié.'
        ], 401);
    }

    // Vérification mot de passe
    if (!Hash::check($request->password, $user->password)) {
        Log::warning("Mot de passe incorrect pour suppression", [
            'user_id' => $user->id
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Mot de passe incorrect.'
        ], 400);
    }

    // 🔥 NE PAS SUPPRIMER L’IMAGE — l’enregistrer telle qu’elle
    UserDelete::create([
        'user_id'         => $user->id,
        'name'            => $user->name,
        'user_name'       => $user->user_name,
        'email'           => $user->email,
        'contact'         => $user->contact,
        'profile_picture' => $user->profile_picture, // ⬅️ Lien conservé
        'additional_data' => json_encode([
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]),
        'deleted_at'      => now()
    ]);

    Log::info("Informations utilisateur archivées dans user_deletes", [
        'user_id' => $user->id
    ]);

    // Suppression des tokens Sanctum
    $user->tokens()->delete();

    // ❌ SUPPRESSION IMAGE : supprimée du code
    // On ne touche plus jamais à l'image

    // Suppression du compte
    try {
        $user->delete();

        Log::info("Utilisateur supprimé définitivement", [
            'user_id' => $user->id
        ]);

    } catch (\Exception $e) {
        Log::error("Erreur lors de la suppression du compte utilisateur", [
            'user_id' => $user->id,
            'error'   => $e->getMessage()
        ]);

        return response()->json([
            'status'  => 'error',
            'message' => 'Une erreur est survenue lors de la suppression.',
            'error'   => $e->getMessage()
        ], 500);
    }

    return response()->json([
        'status'  => 'success',
        'message' => 'Compte supprimé avec succès.'
    ]);
}


}
