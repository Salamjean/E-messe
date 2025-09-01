<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use App\Models\ResetCodePasswordParoisse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthenticateParoisse extends Controller
{
    public function defineAccess($email){
        //Vérification si le sous-admin existe déjà
        $checkSousadminExiste = Paroisse::where('email', $email)->first();

        if($checkSousadminExiste){
            return view('paroisse.auth.defineAcces', compact('email'));
        }else{
            return redirect()->route('paroisse.login');
        };
    }

    public function submitDefineAccess(Request $request){

        // Validation des données
        $request->validate([
                'code'=>'required|exists:reset_code_password_paroisses,code',
                'password' => 'required|same:confirme_password',
                'confirme_password' => 'required|same:password',
            ], [
                'code.required' => 'Le code de réinitialisation est obligatoire.', 
                'code.exists' => 'Le code de réinitialisation est invalide.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.same' => 'Les mots de passe doivent être identiques.',
                'confirme_password.required' => 'Le mot de passe de confirmation est obligatoire.',
                'confirme_password.same' => 'Les mots de passe doivent être identiques.',
            
        ]);
        try {
            $paroisse = Paroisse::where('email', $request->email)->first();

            if ($paroisse) {
                // Mise à jour du mot de passe
                $paroisse->password = Hash::make($request->password);

                // Vérifier si une image est uploadée
                if ($request->hasFile('profile_picture')) {
                    // Supprimer l'ancienne photo si elle existe
                    if ($paroisse->profile_picture) {
                        Storage::delete('public/' . $paroisse->profile_picture);
                    }

                    // Stocker la nouvelle image
                    $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $paroisse->profile_picture = $imagePath;
                }
                $paroisse->update();

                if($paroisse){
                $existingcodehop =  ResetCodePasswordParoisse::where('email', $paroisse->email)->count();

                if($existingcodehop > 1){
                    ResetCodePasswordParoisse::where('email', $paroisse->email)->delete();
                }
                }

                return redirect()->route('paroisse.login')->with('success', 'Vos Accès  on été definir avec succès');
            } else {
                return redirect()->route('paroisse.login')->with('error', 'Email inconnu');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function login(){
        if (auth('paroisse')->check()) {
            return redirect()->route('delivery.dashboard');
        }
        return view('paroisse.auth.login');
    }

    public function handleLogin(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'email' => 'required|exists:paroisses,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Le mail est obligatoire.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);

        try {
            // Récupérer la caisse par son email
            $caisse = Paroisse::where('email', $request->email)->first();

            // Vérifier si la caisse est archivée
            if ($caisse && $caisse->archived_at !== null) {
                return redirect()->back()->with('error', 'Votre compte a été suprrimé. Vous ne pouvez pas vous connecter.');
            }

            // Tenter la connexion
            if (auth('paroisse')->attempt($request->only('email', 'password'))) {
                return redirect()->route('paroisse.dashboard')->with('success', 'Bienvenue sur votre page.');
            } else {
                return redirect()->back()->with('error', 'Votre mot de passe est incorrect.');
            }
        } catch (Exception $e) {
            // Gérer les erreurs
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la connexion.');
        }
    }
}
