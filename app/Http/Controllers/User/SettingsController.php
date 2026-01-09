<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the main settings page.
     */
    public function index()
    {
        return view('user.settings.index');
    }

    /**
     * Display the profile edit page.
     */
    public function editProfile()
    {
        $user = Auth::user();

        return view('user.settings.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users,user_name,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'contact' => 'required|string|max:255',
            'civilite' => 'nullable|string|max:10',
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'user_name.required' => 'Le nom d\'utilisateur est obligatoire.',
            'user_name.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'contact.required' => 'Le contact est obligatoire.',
            'profile_picture.image' => 'Le fichier doit être une image.',
            'profile_picture.max' => 'L\'image ne doit pas dépasser 2048 KB.',
        ]);

        try {
            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $path;
            }

            $user->name = $validated['name'];
            $user->user_name = $validated['user_name'];
            $user->email = $validated['email'];
            $user->contact = $validated['contact'];
            $user->civilite = $validated['civilite'] ?? $user->civilite;

            $user->save();

            return redirect()->route('user.settings.profile')->with('success', 'Profil mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour.')->withInput();
        }
    }

    /**
     * Display the change password page.
     */
    public function password()
    {
        return view('user.settings.password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&.]/',
            ],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.settings.password')->with('success', 'Mot de passe modifié avec succès.');
    }
}
