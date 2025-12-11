<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Paroissien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FicheController extends Controller
{
    /**
     * Show the form for creating or editing the resource.
     */
    public function create()
    {
        $user = Auth::user();
        // Check if the user already has a fiche
        $paroissien = Paroissien::where('user_id', $user->id)->first();
        
        // Use an empty model if none exists, or the existing one
        if (!$paroissien) {
            $paroissien = new Paroissien();
            // Pre-fill some data from User account if available and not set
            $paroissien->nom_prenom = $user->name;
            $paroissien->telephone = $user->contact;
            $paroissien->email = $user->email; // If Paroissien has email, but schema didn't show it. Schema has nom_prenom, telephone, etc.
        }

        return view('user.fiche.create', compact('paroissien'));
    }

    /**
     * Store a newly created or updated resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'situation_matrimoniale' => 'required|string',
            'adresse' => 'required|string',
            'statut_activite' => 'required|string',
            'nom_paroisse' => 'required|string',
            'telephone' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            // Conditional validation could be added here if needed, but nullable fields usually suffice
        ]);

        $data = $request->except(['photo', '_token', '_method']);
        $data['user_id'] = $user->id;
        
        // Handle Booleans
        $data['est_dans_mouvement'] = $request->has('est_dans_mouvement');
        $data['est_baptise'] = $request->has('est_baptise');

        // Handle File Upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos_paroissiens', 'public');
        }

        // Update or Create
        Paroissien::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('user.fiche.create')->with('success', 'Votre fiche a été enregistrée avec succès.');
    }
}
