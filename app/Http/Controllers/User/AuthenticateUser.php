<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticateUser extends Controller
{
    public function register(){
        return view('user.auth.register');
    }
    

    public function handleRegister(Request $request): RedirectResponse
    {
         $validated = $request->validate([
            'name' => 'required',
            'user_name' => 'required|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required',
            'commune' => 'required',
            'CMU' => 'nullable',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      
                'regex:/[A-Z]/',     
                'regex:/[0-9]/',     
                'regex:/[@$!%*#?&.]/',
            ],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ],[
            'name.required' => 'Le nom est obligatoire.',
            'user_name.required' => 'Le prénom est obligatoire.',
            'user_name.unique' => 'Ce nom d\'utilisateur est déjà associée à un compte.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Cette adresse e-mail est déjà associée à un compte.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'contact.required' => 'contact est obligatoire.',
            'commune.required' => 'commune est obligatoire.',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.', // Message d'erreur personnalisé pour regex
            'CMU.required' => 'Le numéro CMU est obligatoire.',
            'profile_picture.image' => 'Le fichier doit être une image.',
            'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'profile_picture.max' => 'L\'image ne doit pas dépasser 2048 KB.',
        ]);

        try {
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                Log::info('Profile picture stored at: ' . $profilePicturePath);
            }
            
            $users = new User();
            $users->name = $request->name;
            $users->user_name = $request->user_name;
            $users->email = $request->email;
            $users->commune = $request->commune;
            $users->indicatif = '+225';
            $users->contact = $request->contact;
            $users->CMU = $request->CMU;
            $users->password = Hash::make($request->password);
            $users->profile_picture = $profilePicturePath;
            $users->save();

            return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès. Vous pouvez vous connecter.');

        } catch (\Exception $e) {
            Log::error('Error during registration: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.'])->withInput();
        }
    }

    public function login(){
        if (auth('web')->check()) {
            return redirect()->route('user.dashboard');
        }
        return view('user.auth.login');
    }

   public function handleLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_name' => ['required', 'string', 'exists:users,user_name'],
            'password' => ['required', 'string'],
        ], [
            'user_name.required' => 'Le nom d\'utilisateur est obligatoire',
            'user_name.exists' => 'Ce nom d\'utilisateur n\'existe pas',
            'password.required' => 'Le mot de passe est obligatoire',
        ]);

        if (!Auth::attempt($request->only('user_name', 'password'), $request->filled('remember'))) {
            return redirect()->route('login')->withErrors([
                'password' => 'Mot de passe incorrect.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard', absolute: false))
                        ->with('success', 'Bienvenue sur votre page!');
    }

}
