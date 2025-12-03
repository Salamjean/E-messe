<?php

namespace App\Http\Controllers\Paroisse\Paroissien;

use App\Exports\ParoissiensExport;
use App\Http\Controllers\Controller;
use App\Models\Paroissien;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ParoissienController extends Controller
{
    /**
     * Affiche la page principale avec le tableau.
     */
    public function index()
    {
        return view('paroisse.paroissiens.index');
    }

    /**
     * Données JSON pour DataTables avec filtres.
     */
    public function data(Request $request)
    {
        // On utilise la méthode commune pour appliquer les filtres (Sexe, Situation)
        $query = $this->getFilteredQuery($request);

        // On sélectionne les colonnes nécessaires
        $query->select([
            'id',
            'nom_prenom',
            'telephone',
            'sexe',
            'situation_matrimoniale',
            'nom_paroisse',
        ])
            ->distinct();

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $showUrl = route('paroissien.show', $row->id);
                $editUrl = route('paroissien.edit', $row->id);
                $deleteUrl = route('paroissien.destroy', $row->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <div class='btn-group'>
                        <a href='{$showUrl}' class='btn btn-info btn-sm me-1' title='Voir'>
                            <i class='fa fa-eye'></i>
                        </a>
                        <a href='{$editUrl}' class='btn btn-warning btn-sm me-1' title='Modifier'>
                            <i class='fa fa-edit'></i>
                        </a>
                        <form action='{$deleteUrl}' method='POST' style='display:inline;' onsubmit='return confirm(\"Êtes-vous sûr de vouloir supprimer ce fidèle ?\")'>
                            {$csrf} {$method}
                            <button type='submit' class='btn btn-danger btn-sm' title='Supprimer'>
                                <i class='fa fa-trash'></i>
                            </button>
                        </form>
                    </div>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        // Assurez-vous que le guard 'paroisse' est bien configuré
        $nom_paroisse = Auth::guard('paroisse')->user()->name ?? 'Paroisse Inconnue';

        return view('paroisse.paroissiens.create', compact('nom_paroisse'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        // Gestion des checkbox/switches
        $data['est_dans_mouvement'] = $request->has('est_dans_mouvement');
        $data['est_baptise'] = $request->has('est_baptise');

        // Nettoyage des champs conditionnels
        if (! $data['est_dans_mouvement']) {
            $data['nom_mouvement'] = null;
        }
        if (! $data['est_baptise']) {
            $data['date_bapteme'] = null;
        }

        // Upload Photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos_paroissiens', 'public');
        }

        Paroissien::create($data);

        return redirect()->route('paroissien.index')->with('success', 'Fidèle enregistré avec succès.');
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
        $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'telephone' => 'required',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        $data['est_dans_mouvement'] = $request->has('est_dans_mouvement');
        $data['est_baptise'] = $request->has('est_baptise');

        if (! $data['est_dans_mouvement']) {
            $data['nom_mouvement'] = null;
        }
        if (! $data['est_baptise']) {
            $data['date_bapteme'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($paroissien->photo) {
                Storage::disk('public')->delete($paroissien->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos_paroissiens', 'public');
        }

        $paroissien->update($data);

        return redirect()->route('paroissien.index')->with('success', 'Fidèle mis à jour avec succès.');
    }

    public function destroy(Paroissien $paroissien)
    {
        if ($paroissien->photo) {
            Storage::disk('public')->delete($paroissien->photo);
        }
        $paroissien->delete();

        return redirect()->route('paroissien.index')->with('success', 'Fidèle supprimé avec succès.');
    }

    /**
     * Méthode privée pour centraliser la logique de filtrage (SQL)
     */
    private function getFilteredQuery(Request $request)
    {
        $query = Paroissien::query()->distinct();

        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->filled('situation_matrimoniale')) {
            $query->where('situation_matrimoniale', $request->situation_matrimoniale);
        }

        return $query;
    }

    public function exportExcel(Request $request)
    {
        $filters = [
            'sexe' => $request->sexe,
            'situation_matrimoniale' => $request->situation_matrimoniale,
            'search' => $request->search_term,
        ];

        return Excel::download(new ParoissiensExport($filters), 'paroissiens_'.date('d-m-Y').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = $this->getFilteredQuery($request);

        if ($request->filled('search_term')) {
            $term = $request->search_term;
            $query->where(function ($q) use ($term) {
                $q->where('nom_prenom', 'LIKE', "%{$term}%")
                    ->orWhere('telephone', 'LIKE', "%{$term}%")
                    ->orWhere('adresse', 'LIKE', "%{$term}%");
            });
        }

        $paroissiens = $query->get();

        $pdf = Pdf::loadView('paroisse.exports.paroissiens.pdf', compact('paroissiens'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('listes_paroissiens_'.date('d-m-Y').'.pdf');
    }
}
