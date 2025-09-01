<?php

namespace App\Http\Controllers\Admin\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use App\Models\ResetCodePasswordParoisse;
use App\Notifications\SendEmailToParoisseAfterRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class ParoisseController extends Controller
{
    public function create()
    {
        return view('admin.paroisse.create');
    }

    public function store(Request $request){
        // Validation des données
        $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|email|unique:paroisses,email',
           'contact' => 'required|string|min:10',
           'localisation' => 'required|string|max:255',
           'profile_picture' => 'nullable|image|max:2048',

        ],[
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Cette adresse e-mail est déjà associée à un compte.',
            'contact.required' => 'Le contact est obligatoire.',
            'contact.min' => 'Le contact doit avoir au moins 10 chiffres.',
            'localisation.required' => 'La localisation est obligatoire.',
            'profile_picture.image' => 'Le fichier doit être une image.',
            'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'profile_picture.max' => 'L\'image ne doit pas dépasser 2048 KB.',
       
       ]);
   
       try {
           // Récupérer le mairie connecté
           $admin = Auth::guard('admin')->user();
   
           if (!$admin || !$admin->name) {
               return redirect()->back()->withErrors(['error' => 'Impossible de récupérer les informations du admin.']);
           }
   
           // Création du docteur
           $paroisse = new Paroisse();
           $paroisse->name = $request->name;
           $paroisse->email = $request->email;
           $paroisse->contact = $request->contact;
           $paroisse->localisation = $request->localisation;
           $paroisse->password = Hash::make('default');
           if ($request->hasFile('profile_picture')) {
               $paroisse->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
           }
           $paroisse->save();
   
           // Envoi de l'e-mail de vérification
           ResetCodePasswordParoisse::where('email', $paroisse->email)->delete();
           $code1 = rand(1000, 4000);
           $code = $code1.''.$paroisse->id;
   
           ResetCodePasswordParoisse::create([
               'code' => $code,
               'email' => $paroisse->email,
           ]);
   
           Notification::route('mail', $paroisse->email)
               ->notify(new SendEmailToParoisseAfterRegistrationNotification($code, $paroisse->email));
   
           return redirect()->route('paroisse.index')->with('success', 'Le livreur a bien été enregistré avec succès.');
       } catch (\Exception $e) {
           return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
       }
    }
}
