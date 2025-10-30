<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;

/**
 * @OA\Info(
 *     title="E_Messe API",
 *     version="1.0.0",
 *     description="API pour l'application E_Messe - Gestion des messes",
 *     @OA\Contact(
 *         email="leprodev03@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8081/api",
 *     description="Serveur Local"
 * )
 *
 * @OA\Server(
 *     url="https://votre-domaine.com/api",
 *     description="Serveur de Production"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum"
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="user_name", type="string", example="johndoe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="indicatif", type="string", example="+225"),
 *     @OA\Property(property="contact", type="string", example="01234567"),
 *     @OA\Property(property="commune", type="string", example="Abobo"),
 *     @OA\Property(property="CMU", type="string", nullable=true, example="CMU12345"),
 *     @OA\Property(property="profile_picture", type="string", nullable=true),
 *     @OA\Property(property="actif", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"login","password"},
 *     @OA\Property(property="login", type="string", example="john@example.com", description="Email ou nom d'utilisateur"),
 *     @OA\Property(property="password", type="string", format="password", example="password123")
 * )
 *
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"name","user_name","email","indicatif","contact","commune","password","password_confirmation"},
 *     @OA\Property(property="name", type="string", maxLength=191, example="John Doe"),
 *     @OA\Property(property="user_name", type="string", maxLength=191, example="johndoe"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=191, example="john@example.com"),
 *     @OA\Property(property="indicatif", type="string", maxLength=10, example="+225"),
 *     @OA\Property(property="contact", type="string", maxLength=191, example="01234567"),
 *     @OA\Property(property="commune", type="string", maxLength=191, example="Abobo"),
 *     @OA\Property(property="password", type="string", format="password", minLength=6, example="password123"),
 *     @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
 *     @OA\Property(property="CMU", type="string", maxLength=191, nullable=true, example="CMU12345"),
 *     @OA\Property(property="profile_picture", type="string", maxLength=191, nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="success"),
 *     @OA\Property(property="message", type="string", example="Connexion réussie."),
 *     @OA\Property(property="access_token", type="string", example="1|abc123..."),
 *     @OA\Property(property="token_type", type="string", example="Bearer"),
 *     @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="error"),
 *     @OA\Property(property="message", type="string", example="Message d'erreur")
 * )
 */

class AuthController extends Controller
{
    /**
     * Connexion avec email ou user_name
     */



        /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion utilisateur",
     *     description="Authentifie un utilisateur avec email/nom d'utilisateur et mot de passe",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login","password"},
     *             @OA\Property(property="login", type="string", example="john@example.com ou johndoe"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Connexion réussie."),
     *             @OA\Property(property="access_token", type="string", example="1|abc123..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="user_name", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="contact", type="string", example="+22501234567"),
     *                 @OA\Property(property="commune", type="string", example="Abobo"),
     *                 @OA\Property(property="profile_picture", type="string", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants incorrects",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Identifiants incorrects.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Compte inactif",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Compte inactif. Contactez l'administration.")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
                    ->orWhere('user_name', $request->login)
                    ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identifiants incorrects.',
            ], 401);
        }

        if (!$user->actif) {
            return response()->json([
                'status' => 'error',
                'message' => 'Compte inactif. Contactez l’administration.',
            ], 403);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Connexion réussie.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'contact' => $user->contact,
                'commune' => $user->commune,
                'profile_picture' => $user->profile_picture,
            ]
        ], 200);
    }

    /**
     * Enregistrement d'un utilisateur
     */


        /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Inscription utilisateur",
     *     description="Crée un nouveau compte utilisateur",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","user_name","email","indicatif","contact","commune","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", maxLength=191, example="John Doe"),
     *             @OA\Property(property="user_name", type="string", maxLength=191, example="johndoe"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=191, example="john@example.com"),
     *             @OA\Property(property="indicatif", type="string", maxLength=10, example="+225"),
     *             @OA\Property(property="contact", type="string", maxLength=191, example="01234567"),
     *             @OA\Property(property="commune", type="string", maxLength=191, example="Abobo"),
     *             @OA\Property(property="password", type="string", format="password", minLength=6, example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(property="CMU", type="string", maxLength=191, nullable=true, example="CMU12345"),
     *             @OA\Property(property="profile_picture", type="string", maxLength=191, nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inscription réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Inscription réussie ✅"),
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
     *                 @OA\Property(property="actif", type="integer", example=1)
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abc123...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:191',
            'user_name'       => 'required|string|max:191|unique:users,user_name',
            'email'           => 'required|email|max:191|unique:users,email',
            'indicatif'       => 'required|string|max:10',
            'contact'         => 'required|string|max:191|unique:users,contact',
            'commune'         => 'required|string|max:191',
            'password'        => 'required|min:8|confirmed',
            'CMU'             => 'nullable|string|max:191',
            'profile_picture' => 'nullable|string|max:191',
        ]);

        $user = User::create([
            'name'            => $validated['name'],
            'user_name'       => $validated['user_name'],
            'email'           => $validated['email'],
            'indicatif'       => $validated['indicatif'],
            'contact'         => $validated['contact'],
            'CMU'             => $validated['CMU'] ?? null,
            'profile_picture' => $validated['profile_picture'] ?? null,
            'commune'         => $validated['commune'],
            'password'        => bcrypt($validated['password']),
            'actif'           => 1,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie ✅',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    /**
     * Profil utilisateur connecté
     */


    /**
     * Déconnexion
     */

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion",
     *     description="Déconnecte l'utilisateur et supprime ses tokens",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Utilisateur non authentifié")
     *         )
     *     )
     * )
     */

public function logout(Request $request)
{
    $user = $request->user();

    // Debug headers et token
    Log::info('Headers reçus pour logout', $request->headers->all());
    Log::info('User récupéré pour logout', ['user' => $user]);

    if (!$user) {
        return response()->json(['error' => 'Utilisateur non authentifié'], 401);
    }

    $user->tokens()->delete();

    return response()->json(['message' => 'Déconnexion réussie']);
}




    /**
     * Mot de passe oublié - Envoi du lien
     */

        /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     summary="Mot de passe oublié",
     *     description="Envoie un lien de réinitialisation par email",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email envoyé",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Un lien de réinitialisation a été envoyé à votre e-mail."),
     *             @OA\Property(property="token_demo", type="string", example="abc123...", description="À supprimer en production")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Aucun utilisateur trouvé avec cet e-mail.")
     *         )
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aucun utilisateur trouvé avec cet e-mail.',
            ], 404);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Envoi du mail (simplifié, tu peux personnaliser plus tard)
        Mail::raw("Voici votre lien de réinitialisation : {$token}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Réinitialisation de mot de passe');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Un lien de réinitialisation a été envoyé à votre e-mail.',
            'token_demo' => $token // ⚠️ à supprimer en production !
        ]);
    }

    /**
     * Réinitialisation du mot de passe
     */

        /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Réinitialisation du mot de passe",
     *     description="Réinitialise le mot de passe avec le token reçu par email",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","token","password","password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="token", type="string", example="abc123..."),
     *             @OA\Property(property="password", type="string", format="password", minLength=6, example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe réinitialisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mot de passe réinitialisé avec succès ✅")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Token invalide",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token invalide.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email ou utilisateur non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Aucune demande trouvée.")
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$reset) {
            return response()->json(['message' => 'Aucune demande trouvée.'], 404);
        }

        // Vérifie le token
        if (!Hash::check($request->token, $reset->token)) {
            return response()->json(['message' => 'Token invalide.'], 400);
        }

        // Met à jour le mot de passe
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Supprime le token utilisé
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès ✅']);
    }



    /**
 * @OA\Info(
 *     title="E_Messe API",
 *     version="1.0.0",
 *     description="API pour l'application E_Messe - Gestion des messes",
 *     @OA\Contact(
 *         email="leprodev03@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8081/api",
 *     description="Serveur Local"
 * )
 *
 * @OA\Server(
 *     url="https://votre-domaine.com/api",
 *     description="Serveur de Production"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="user_name", type="string", example="johndoe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="indicatif", type="string", example="+225"),
 *     @OA\Property(property="contact", type="string", example="01234567"),
 *     @OA\Property(property="commune", type="string", example="Abobo"),
 *     @OA\Property(property="CMU", type="string", nullable=true, example="CMU12345"),
 *     @OA\Property(property="profile_picture", type="string", nullable=true),
 *     @OA\Property(property="actif", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
}
