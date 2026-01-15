<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // 1. Rediriger l'utilisateur vers Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Gérer le retour de Google
    public function callback()
    {
        \Log::info('Google Auth: Début du callback');
        try {
            // Récupérer les infos de l'utilisateur Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            \Log::info('Google Auth: Utilisateur récupéré de Google', ['email' => $googleUser->getEmail(), 'id' => $googleUser->getId()]);

            // Chercher l'utilisateur dans la BDD par son google_id ou son email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (! $user) {
                \Log::info('Google Auth: Création d\'un nouvel utilisateur');
                // Si l'utilisateur n'existe pas, on le crée
                $baseUserName = Str::slug($googleUser->getName() ?? explode('@', $googleUser->getEmail())[0], '');
                $userName = $baseUserName;
                $counter = 1;

                while (User::where('user_name', $userName)->exists()) {
                    $userName = $baseUserName.$counter;
                    $counter++;
                }

                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Utilisateur Google',
                    'user_name' => $userName,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'contact' => '00000000',
                    'password' => bcrypt(Str::random(16)),
                    'actif' => 1,
                ]);
                \Log::info('Google Auth: Utilisateur créé avec succès', ['id' => $user]);
            } else {
                \Log::info('Google Auth: Utilisateur existant trouvé', ['id' => $user]);
                // Si l'utilisateur existe mais n'a pas de google_id (ex: inscrit par email avant), on le met à jour
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                    \Log::info('Google Auth: google_id mis à jour pour l\'utilisateur');
                }

                // Mettre à jour le statut actif
                $user->update(['actif' => 1]);
            }

            // Vérifier si l'utilisateur est archivé
            if ($user->isArchived()) {
                \Log::warning('Google Auth: Tentative de connexion d\'un utilisateur archivé', ['id' => $user->id]);

                return redirect()->route('login')->with('error', 'Votre compte a été archivé. Veuillez contacter l\'administration.');
            }

            // Connecter l'utilisateur
            Auth::login($user);
            \Log::info('Google Auth: Utilisateur connecté, redirection vers dashboard');

            // Redirection vers le tableau de bord
            return redirect()->route('user.dashboard');

        } catch (\Exception $e) {
            // Log de l'erreur détaillée pour le débogage
            \Log::error('Google Auth ERREUR: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            // En cas d'erreur (annulation par l'utilisateur, etc.)
            return redirect()->route('login')->with('error', 'Erreur de connexion Google : '.$e->getMessage());
        }
    }
}
