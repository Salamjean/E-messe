<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use App\Models\ResetCodePasswordParoisse;
use App\Notifications\SendEmailToParoisseAfterRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Models\Ville;
use App\Models\Commune;
use Illuminate\Support\Facades\Log;

class ParoisseController extends Controller
{
    public function index()
    {
        // On charge les paroisses avec leur commune et la ville de la commune
        $paroisses = Paroisse::with('commune.ville')->get();
    
        // On récupère les communes qui ont au moins une paroisse
        $communeIds = Paroisse::distinct()->pluck('commune_id');
        $communes = Commune::whereIn('id', $communeIds)->with('ville')->orderBy('nom_commune')->get();
        
        // Gestion du tri (reste inchangée)
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
        
        // On passe la nouvelle variable 'communes' à la vue
        return view('admin.paroisse.index', compact('paroisses', 'communes'));
    }

    public function create()
    {
        // Récupérer toutes les villes pour la liste déroulante
        $villes = Ville::orderBy('nom_ville')->get();
        return view('admin.paroisse.create', compact('villes'));
    }

    // NOUVELLE MÉTHODE POUR L'APPEL AJAX
    public function getCommunesByVille($ville_id)
    {
        $communes = Commune::where('ville_id', $ville_id)->orderBy('nom_commune')->get();
        return response()->json($communes);
    }
    
    public function store(Request $request)
{
    Log::info('Début de la création d\'une nouvelle paroisse', ['ip' => $request->ip(), 'user_agent' => $request->userAgent()]);

    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255|unique:paroisses,name',
        'email' => 'required|email|unique:paroisses,email',
        'contact' => 'required|string|min:10',
        'commune_id' => 'required|exists:communes,id',
        'profile_picture' => 'nullable|image|max:2048',
    ], [
        'name.required' => 'Le nom est obligatoire.',
        'name.unique' => 'Cette paroisse est déjà inscrite.',
        'email.required' => 'L\'adresse e-mail est obligatoire.',
        'email.email' => 'L\'adresse e-mail n\'est pas valide.',
        'email.unique' => 'Cette adresse e-mail est déjà associée à un compte.',
        'contact.required' => 'Le contact est obligatoire.',
        'contact.min' => 'Le contact doit avoir au moins 10 chiffres.',
        'profile_picture.image' => 'Le fichier doit être une image.',
        'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
        'profile_picture.max' => 'L\'image ne doit pas dépasser 2048 KB.',
        'commune_id.required' => 'La commune est obligatoire.',
    ]);

    Log::info('Validation des données réussie', [
        'name' => $request->name,
        'email' => $request->email,
        'commune_id' => $request->commune_id
    ]);

    try {
        // Récupérer le mairie connecté
        $admin = Auth::guard('admin')->user();
        Log::info('Admin authentifié', ['admin_id' => $admin->id ?? 'null', 'admin_name' => $admin->name ?? 'null']);

        if (!$admin || !$admin->name) {
            Log::warning('Admin non authentifié ou informations manquantes', ['admin' => $admin]);
            return redirect()->back()->withErrors(['error' => 'Impossible de récupérer les informations du admin.']);
        }

        // Création de la paroisse
        $paroisse = new Paroisse();
        $paroisse->name = $request->name;
        $paroisse->email = $request->email;
        $paroisse->contact = $request->contact;
        $paroisse->commune_id = $request->commune_id;
        $paroisse->password = Hash::make('default');

        Log::info('Données de la paroisse préparées', [
            'name' => $paroisse->name,
            'email' => $paroisse->email,
            'commune_id' => $paroisse->commune_id
        ]);

        // Gestion de l'image de profil
        if ($request->hasFile('profile_picture')) {
            Log::info('Traitement de l\'image de profil', [
                'file_name' => $request->file('profile_picture')->getClientOriginalName(),
                'file_size' => $request->file('profile_picture')->getSize()
            ]);
            
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $paroisse->profile_picture = $profilePicturePath;
            Log::info('Image de profil sauvegardée', ['path' => $profilePicturePath]);
        } else {
            Log::info('Aucune image de profil fournie');
        }

        // Sauvegarde de la paroisse
        $paroisse->save();
        Log::info('Paroisse créée avec succès', [
            'paroisse_id' => $paroisse->id,
            'paroisse_name' => $paroisse->name
        ]);

        // Génération et envoi du code de vérification
        Log::info('Début du processus d\'envoi d\'email de vérification');
        
        // Suppression des anciens codes
        $deletedCodes = ResetCodePasswordParoisse::where('email', $paroisse->email)->delete();
        Log::info('Anciens codes supprimés', ['count' => $deletedCodes]);

        // Génération du code
        $code1 = rand(1000, 4000);
        $code = $code1 . '' . $paroisse->id;
        Log::info('Code de vérification généré', ['code' => $code]);

        // Création du code dans la base
        ResetCodePasswordParoisse::create([
            'code' => $code,
            'email' => $paroisse->email,
        ]);
        Log::info('Code de vérification sauvegardé en base');

        // Envoi de la notification
        try {
            Notification::route('mail', $paroisse->email)
                ->notify(new SendEmailToParoisseAfterRegistrationNotification($code, $paroisse->email));
            
            Log::info('Email de vérification envoyé avec succès', [
                'email' => $paroisse->email,
                'code' => $code
            ]);
        } catch (\Exception $emailException) {
            Log::error('Erreur lors de l\'envoi de l\'email', [
                'email' => $paroisse->email,
                'error' => $emailException->getMessage(),
                'trace' => $emailException->getTraceAsString()
            ]);
            
            // On continue malgré l'erreur d'email
            Log::warning('Continuer malgré l\'erreur d\'envoi d\'email');
        }

        Log::info('Création de paroisse terminée avec succès', [
            'paroisse_id' => $paroisse->id,
            'admin_id' => $admin->id
        ]);

        return redirect()->route('paroisse.index')->with('success', 'La paroisse a bien été enregistrée avec succès.');

    } catch (\Exception $e) {
        Log::error('Erreur lors de la création de la paroisse', [
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'request_data' => $request->all(),
            'admin_id' => $admin->id ?? 'null'
        ]);

        return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
    }
}
    public function edit($paroisse)
{
    try {
        $paroisse = Paroisse::findOrFail($paroisse);
        $villes = Ville::orderBy('nom_ville')->get();
        return view('admin.paroisse.edit', compact('paroisse','villes'));
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('paroisse.index') // Correction ici aussi
            ->with('error', 'Paroisse non trouvée.');
    }
}

public function update(Request $request, $id)
{
    Log::info('=== DÉBUT MISE À JOUR PAROISSE ===', ['id' => $id]);
    
    try {
        Log::info('Recherche de la paroisse', ['id' => $id]);
        $paroisse = Paroisse::findOrFail($id);
        Log::info('Paroisse trouvée', ['paroisse_id' => $paroisse->id, 'nom' => $paroisse->name]);

        Log::info('Données reçues', $request->all());
        
        // Validation des données
        Log::info('Début validation');
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:paroisses,name,' . $id,
            'commune_id' => 'required|exists:communes,id',
            'contact' => 'required|string|max:255',
            'email' => 'required|email|unique:paroisses,email,' . $id,
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'name.required' => 'Le nom de la paroisse est obligatoire.',
            'name.unique' => 'Ce nom de paroisse est déjà utilisé.',
            'commune_id.required' => 'La commune est obligatoire.',
            'contact.required' => 'Le contact est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'profile_picture.image' => 'Le fichier doit être une image.',
            'profile_picture.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'profile_picture.max' => 'L\'image ne doit pas dépasser 2MB.',
        ]);
        Log::info('Validation réussie', $validated);

        // Traitement de l'image de profil
        if ($request->hasFile('profile_picture')) {
            Log::info('Nouvelle image détectée');
            
            // Supprimer l'ancienne image si elle existe
            if ($paroisse->profile_picture && Storage::disk('public')->exists($paroisse->profile_picture)) {
                Log::info('Suppression ancienne image', ['path' => $paroisse->profile_picture]);
                Storage::disk('public')->delete($paroisse->profile_picture);
            }
            
            // Stocker la nouvelle image
            $imagePath = $request->file('profile_picture')->store('paroisses/profile_pictures', 'public');
            $paroisse->profile_picture = $imagePath;
            Log::info('Nouvelle image stockée', ['path' => $imagePath]);
        }

        // Mise à jour des données
        Log::info('Mise à jour des données', [
            'ancien_nom' => $paroisse->name,
            'nouveau_nom' => $validated['name'],
            'ancien_email' => $paroisse->email,
            'nouvel_email' => $validated['email']
        ]);
        
        $paroisse->name = $validated['name'];
        $paroisse->commune_id = $validated['commune_id'];
        $paroisse->contact = $validated['contact'];
        $paroisse->email = $validated['email'];
        
        Log::info('Sauvegarde en base de données');
        $paroisse->save();
        Log::info('Sauvegarde réussie');

        Log::info('=== MISE À JOUR TERMINÉE AVEC SUCCÈS ===');
        
        return redirect()->route('paroisse.index')
            ->with('success', 'Paroisse mise à jour avec succès.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Erreur de validation', [
            'errors' => $e->errors(),
            'input' => $request->all()
        ]);
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();
            
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Paroisse non trouvée', ['id' => $id, 'error' => $e->getMessage()]);
        return redirect()->route('paroisse.index')
            ->with('error', 'Paroisse non trouvée.');
            
    } catch (\Exception $e) {
        Log::error('Erreur générale lors de la mise à jour', [
            'id' => $id,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
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
