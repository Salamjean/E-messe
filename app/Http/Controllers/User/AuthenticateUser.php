<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgotPasswordUserNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthenticateUser extends Controller
{
    public function register()
    {
        return view('user.auth.register');
    }

    public function handleRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'user_name' => 'required|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
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
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.', // Message d'erreur personnalisé pour regex
            'profile_picture.image' => 'Le fichier doit être une image.',
            'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'profile_picture.max' => 'L\'image ne doit pas dépasser 2048 KB.',
        ]);

        try {
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                Log::info('Profile picture stored at: '.$profilePicturePath);
            }

            $users = new User;
            $users->name = $request->name;
            $users->user_name = $request->user_name;
            $users->email = $request->email;
            $users->contact = $request->contact;
            $users->password = Hash::make($request->password);
            $users->profile_picture = $profilePicturePath;
            $users->save();

            return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');

        } catch (\Exception $e) {
            Log::error('Error during registration: '.$e->getMessage());

            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.'])->withInput();
        }
    }

    public function login()
    {
        if (auth('web')->check()) {
            return redirect()->route('user.dashboard');
        }

        return view('user.auth.login');
    }

    public function handleLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login_id.required' => 'L\'email ou le nom d\'utilisateur est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
        ]);

        $login_type = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        // Attempt to log the user in
        if (! Auth::attempt([$login_type => $request->login_id, 'password' => $request->password], $request->filled('remember'))) {
            // Check if user exists to give specific error
            $userExists = User::where($login_type, $request->login_id)->exists();

            if (! $userExists) {
                return redirect()->back()->withErrors([
                    'login_id' => 'Ce compte n\'existe pas.',
                ])->withInput();
            }

            return redirect()->back()->withErrors([
                'password' => 'Mot de passe incorrect.',
            ])->withInput();
        }

        // Mettre à jour le statut actif à 1
        $user = Auth::user();
        $user->actif = 1;
        $user->save();

        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard', absolute: false))
            ->with('success', 'Bienvenue sur votre page!')
            ->with('show_tutorial_popup', true);
    }

    public function editProfile()
    {
        return view('user.auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users,user_name,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'contact' => 'required|string|max:255',
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'password' => [
                'nullable',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&.]/',
            ],
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (! Hash::check($value, $user->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'user_name.required' => 'Le nom d\'utilisateur est obligatoire.',
            'user_name.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'contact.required' => 'Le contact est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.',
            'current_password.required' => 'Le mot de passe actuel est requis pour confirmer les modifications.',
            'profile_picture.image' => 'Le fichier doit être une image.',
            'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'profile_picture.max' => 'L\'image ne doit pas dépasser 2048 KB.',
        ]);

        try {
            // Mettre à jour l'image de profil si fournie
            if ($request->hasFile('profile_picture')) {
                // Supprimer l'ancienne image si elle existe
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }

                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $profilePicturePath;
            }

            // Mettre à jour les autres champs
            $user->name = $validated['name'];
            $user->user_name = $validated['user_name'];
            $user->email = $validated['email'];
            $user->contact = $validated['contact'];

            // Mettre à jour le mot de passe si fourni
            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->back()->with('success', 'Profil mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Error updating profile: '.$e->getMessage());

            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.'])->withInput();
        }
    }

    public function showForgotPasswordForm()
    {
        return view('user.auth.forgot_password');
    }

    /**
     * Traite la demande de réinitialisation de mot de passe
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Aucun utilisateur trouvé avec cet e-mail.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Générer un OTP
        $otp = rand(100000, 999999);
        $token = Hash::make($otp);

        // Stocker le token
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        // Envoyer l'email
        try {
            $user->notify(new ForgotPasswordUserNotification($otp));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email OTP: '.$e->getMessage());

            return back()->withErrors(['email' => 'Impossible d\'envoyer l\'e-mail. Veuillez réessayer.']);
        }

        return redirect()->route('verify-otp.form')->with([
            'email' => $user->email,
            'success' => 'Un code de vérification a été envoyé à votre adresse e-mail.',
        ]);
    }

    /**
     * Affiche le formulaire de vérification OTP
     */
    public function showVerifyOtpForm()
    {
        if (! session('email')) {
            return redirect()->route('forgot-password.form');
        }

        return view('user.auth.verify_otp');
    }

    /**
     * Vérifie l'OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Code OTP invalide ou expiré.']);
        }

        // Vérifier si le token a expiré (15 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors(['otp' => 'Le code OTP a expiré. Veuillez en demander un nouveau.']);
        }

        return redirect()->route('reset-password.form')->with('email', $request->email);
    }

    /**
     * Affiche le formulaire de réinitialisation de mot de passe
     */
    public function showResetPasswordForm()
    {
        if (! session('email')) {
            return redirect()->route('forgot-password.form');
        }

        return view('user.auth.reset_password');
    }

    /**
     * Réinitialise le mot de passe
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // Au moins une minuscule
                'regex:/[A-Z]/',      // Au moins une majuscule
                'regex:/[0-9]/',      // Au moins un chiffre
                'regex:/[@$!%*#?&.]/', // Au moins un caractère spécial
            ],
        ], [
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        // Supprimer le token après réinitialisation
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Retourner une réponse JSON pour SweetAlert au lieu de rediriger immédiatement
        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé avec succès. Vous pouvez maintenant vous connecter.',
            'redirect_url' => route('login'),
        ]);
    }
}
