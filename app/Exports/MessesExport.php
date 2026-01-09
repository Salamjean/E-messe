<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MessesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $type;

    protected $start_date;

    protected $end_date;

    public function __construct($type, $start_date = null, $end_date = null)
    {
        $this->type = $type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function query()
    {
        $query = Auth::guard('paroisse')->user()->messes()->orderBy('created_at', 'desc');

        switch ($this->type) {
            case 'en_attente_confirmation':
                $query->where('statut', 'en attente');
                break;
            case 'a_celebrer':
                $query->where('statut', 'confirmee')
                    ->whereDate('date_souhaitee', '<=', now());
                break;
            case 'en_attente_celebration':
                $query->where('statut', 'confirmee')
                    ->whereDate('date_souhaitee', '>=', now());
                break;
            case 'historique':
                $query->whereNotIn('statut', ['en attente', 'confirmee', 'en_attente_paiement']);
                break;
        }

        if ($this->start_date) {
            $query->whereDate('date_souhaitee', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('date_souhaitee', '<=', $this->end_date);
        }

        return $query;
    }

    public function map($messe): array
    {
        // Traitement des noms
        $noms = $messe->nom_prenom_concernes;
        if (is_string($noms)) {
            $decoded = json_decode($noms, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $noms = implode(', ', $decoded);
            }
        } elseif (is_array($noms)) {
            $noms = implode(', ', $noms);
        }

        return [
            $messe->created_at->format('d/m/Y'),
            $messe->user ? $messe->user->name : 'Anonyme',
            $messe->date_souhaitee ? \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') : '-',
            $messe->heure_souhaitee ?? '-',
            $messe->celebration_choisie,
            $noms,
            $messe->motif_intention ?? '-',
            $messe->statut,
            $messe->montant_offrande,
        ];
    }

    public function headings(): array
    {
        return [
            'Date Demande',
            'Demandeur',
            'Date Souhaitée',
            'Heure',
            'Intention',
            'Noms Concernés',
            'Motif',
            'Statut',
            'Montant (FCFA)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Ajouter une ligne de totaux à la fin
        $highestRow = $sheet->getHighestRow();
        $totalRow = $highestRow + 1;

        $sheet->setCellValue('A'.$totalRow, 'TOTAL');
        $sheet->setCellValue('H'.$totalRow, 'Total Messes: '.($highestRow - 1));
        $sheet->setCellValue('I'.$totalRow, '=SUM(I2:I'.$highestRow.')');

        return [
            1 => ['font' => ['bold' => true]],
            $totalRow => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFF0000']]],
        ];
    }
}
