<?php

namespace App\Exports;

use App\Models\Paroissien;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParoissiensExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Paroissien::query();

        // Filtre Sexe
        if (! empty($this->filters['sexe'])) {
            $query->where('sexe', $this->filters['sexe']);
        }

        // Filtre Situation Matrimoniale
        if (! empty($this->filters['situation_matrimoniale'])) {
            $query->where('situation_matrimoniale', $this->filters['situation_matrimoniale']);
        }

        // Recherche globale (Barre de recherche DataTables)
        if (! empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nom_prenom', 'LIKE', "%{$search}%")
                    ->orWhere('telephone', 'LIKE', "%{$search}%")
                    ->orWhere('adresse', 'LIKE', "%{$search}%");
            });
        }

        // Filtre Paroisse (Sécurité)
        if (! empty($this->filters['nom_paroisse'])) {
            $query->where('nom_paroisse', $this->filters['nom_paroisse']);
        }

        return $query;
    }

    public function map($paroissien): array
    {
        return [
            $paroissien->id,
            $paroissien->nom_prenom,
            $paroissien->date_naissance,
            $paroissien->sexe,
            $paroissien->situation_matrimoniale,
            $paroissien->adresse,
            $paroissien->statut_activite,
            $paroissien->telephone,
            $paroissien->est_dans_mouvement ? 'Oui' : 'Non',
            $paroissien->nom_mouvement,
            $paroissien->est_baptise ? 'Oui' : 'Non',
            $paroissien->date_bapteme,
            $paroissien->nom_paroisse_bapteme,
        ];
    }

    public function headings(): array
    {
        return [
            'ID', 'Nom & Prénoms', 'Date Naissance', 'Sexe', 'Situation Matrimoniale',
            'Adresse', 'Statut Activité', 'Téléphone',
            'Dans un mouvement ?', 'Nom Mouvement', 'Baptisé ?', 'Date Baptême',
            'Paroisse Baptême',
        ];
    }
}
