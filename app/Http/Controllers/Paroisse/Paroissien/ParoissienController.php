<?php

namespace App\Http\Controllers\Paroisse\Paroissien;

use App\Http\Controllers\Controller;
use App\Models\Paroissien;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Paroisse;
use Illuminate\Support\Facades\Storage;

class ParoissienController extends Controller
{
    public function index()
    {
        return view('paroisse.paroissiens.index');
    }

    // Logique pour DataTables AJAX
    public function data()
    {
        $paroissiens = Paroissien::select(['id', 'nom_prenom', 'telephone', 'nom_paroisse', 'situation_matrimoniale']);

        return datatables()->of($paroissiens)
            ->addColumn('action', function ($row) {
                $btn = '<a href="'.route('paroissien.show', $row->id).'" class="btn btn-info btn-sm me-1">Voir</a>';
                $btn .= '<a href="'.route('paroissien.edit', $row->id).'" class="btn btn-warning btn-sm me-1">Edit</a>';
                $btn .= '<form action="'.route('paroissien.destroy', $row->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr ?\')">
                            '.csrf_field().' '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm">Sup</button>
                         </form>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $nom_paroisse =  Auth::guard('paroisse')->user()->name;  
        return view('paroisse.paroissiens.create', compact('nom_paroisse'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_prenom' => 'required|string',
            'telephone' => 'required',
            'photo' => 'nullable|image|max:2048',
            
        ]);

        $data = $request->except('photo');

        // Gestion des checkbox switch (retournent "on" ou null)
        $data['est_dans_mouvement'] = $request->has('est_dans_mouvement');
        $data['est_baptise'] = $request->has('est_baptise');

        // Nettoyage si switch OFF
        if (! $data['est_dans_mouvement']) {
            $data['nom_mouvement'] = null;
        }
        if (! $data['est_baptise']) {
            $data['date_bapteme'] = null;
        }

        // Upload Photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos_paroissiens', 'public');
            $data['photo'] = $path;
        }

        Paroissien::create($data);

        return redirect()->route('paroissien.create')->with('success', 'Fidèle enregistré avec succès.');
    }

    public function show(Paroissien $paroissien)
    {
        return view('paroisse.paroissiens.show', compact('paroissien'));
    }

    public function edit(Paroissien $paroissien)
    {
        return view('paroisse.paroissiens.edit', compact('paroissien'));
    }

    public function update(Request $request, Paroissien $paroissien)
    {
        // Validation similaire au store...
        $data = $request->except('photo');

        // Gestion Switch
        $data['est_dans_mouvement'] = $request->has('est_dans_mouvement');
        $data['est_baptise'] = $request->has('est_baptise');

        if (! $data['est_dans_mouvement']) {
            $data['nom_mouvement'] = null;
        }
        if (! $data['est_baptise']) {
            $data['date_bapteme'] = null;
        }

        // Update Photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si nécessaire
            if ($paroissien->photo) {
                Storage::disk('public')->delete($paroissien->photo);
            }

            $path = $request->file('photo')->store('photos_paroissiens', 'public');
            $data['photo'] = $path;
        }

        $paroissien->update($data);

        return redirect()->route('paroissien.index')->with('success', 'Fidèle mis à jour.');
    }

    public function destroy(Paroissien $paroissien)
    {
        if ($paroissien->photo) {
            Storage::disk('public')->delete($paroissien->photo);
        }
        $paroissien->delete();

        return redirect()->route('paroissien.index')->with('success', 'Supprimé avec succès.');
    }

    // Méthodes placeholder pour export
    public function exportPdf()
    {
        return 'Logique PDF ici (DomPDF)';
    }

    public function exportExcel()
    {
        return 'Logique Excel ici (Maatwebsite)';
    }
}
