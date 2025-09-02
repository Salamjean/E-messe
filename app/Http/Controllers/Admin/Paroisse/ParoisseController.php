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
use Illuminate\Support\Facades\Storage;

class ParoisseController extends Controller
{
    public function index()
    {
        $paroisses = Paroisse::all();
        $localisations = Paroisse::distinct()->pluck('localisation');
        
        // Gestion du tri
        $sort = request()->get('sort');
        if ($sort) {
            switch ($sort) {
                case 'name':
                    $paroisses = $paroisses->sortBy('name');
                    break;
                case 'name_desc':
                    $paroisses = $paroisses->sortByDesc('name');
                    break;
                case 'recent':
                    $paroisses = $paroisses->sortByDesc('created_at');
                    break;
                case 'oldest':
                    $paroisses = $paroisses->sortBy('created_at');
                    break;
            }
        }
        
        return view('admin.paroisse.index', compact('paroisses', 'localisations'));
    }

    public function create()
    {
        return view('admin.paroisse.create');
    }

    public function store(Request $request){
        // Validation des données
        $request->validate([
           'name' => 'required|string|max:255|unique:paroisses,name',
           'email' => 'required|email|unique:paroisses,email',
           'contact' => 'required|string|min:10',
           'localisation' => 'required|string|max:255',
           'profile_picture' => 'nullable|image|max:2048',

        ],[
            'name.required' => 'Le nom est obligatoire.',
            'name.unique' => 'Cette paroisse est déjà inscrite.',
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

    public function edit($id)
    {
        try {
            $paroisse = Paroisse::findOrFail($id);
            return view('admin.paroisse.edit', compact('paroisse'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.paroisses.index')
                ->with('error', 'Paroisse non trouvée.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $paroisse = Paroisse::findOrFail($id);
            
            // Validation des données
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:paroisses,name,' . $paroisse->id,
                'localisation' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'email' => 'required|email|unique:paroisses,email,' . $paroisse->id,
                'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ], [
                'name.required' => 'Le nom de la paroisse est obligatoire.',
                'name.unique' => 'Ce nom de paroisse est déjà utilisé.',
                'localisation.required' => 'La localisation est obligatoire.',
                'contact.required' => 'Le contact est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être une adresse valide.',
                'email.unique' => 'Cet email est déjà utilisé.',
                'montant_offrande.numeric' => 'Le montant de l\'offrande doit être un nombre.',
                'montant_offrande.min' => 'Le montant de l\'offrande ne peut pas être négatif.',
                'profile_picture.image' => 'Le fichier doit être une image.',
                'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
                'profile_picture.max' => 'L\'image ne doit pas dépasser 2MB.',
            ]);

            // Traitement de l'image de profil
            if ($request->hasFile('profile_picture')) {
                // Supprimer l'ancienne image si elle existe
                if ($paroisse->profile_picture && Storage::disk('public')->exists($paroisse->profile_picture)) {
                    Storage::disk('public')->delete($paroisse->profile_picture);
                }
                
                // Stocker la nouvelle image
                $imagePath = $request->file('profile_picture')->store('paroisses/profile_pictures', 'public');
                $paroisse->profile_picture = $imagePath;
            }

            // Mise à jour des données
            $paroisse->name = $validated['name'];
            $paroisse->localisation = $validated['localisation'];
            $paroisse->contact = $validated['contact'];
            $paroisse->email = $validated['email'];
            
            $paroisse->save();

            return redirect()->route('paroisse.index')
                ->with('success', 'Paroisse mise à jour avec succès.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('paroisse.index')
                ->with('error', 'Paroisse non trouvée.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $paroisse = Paroisse::findOrFail($id);
            
            // Supprimer l'image de profil si elle existe
            if ($paroisse->profile_picture && Storage::disk('public')->exists($paroisse->profile_picture)) {
                Storage::disk('public')->delete($paroisse->profile_picture);
            }
            
            $paroisse->delete();

            return redirect()->route('paroisse.index')
                ->with('success', 'Paroisse supprimée avec succès.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('paroisse.index')
                ->with('error', 'Paroisse non trouvée.');
        } catch (\Exception $e) {
            return redirect()->route('paroisse.index')
                ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }
}
