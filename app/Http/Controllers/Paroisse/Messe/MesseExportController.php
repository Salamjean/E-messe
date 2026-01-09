<?php

namespace App\Http\Controllers\Paroisse\Messe;

use App\Exports\MessesExport;
use App\Exports\ParoissiensExport;
use App\Http\Controllers\Controller;
use App\Models\Paroissien;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MesseExportController extends Controller
{
    // --- PAROISSIENS EXPORTS ---

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

    private function getFilteredQuery(Request $request)
    {
        $query = Paroissien::query();

        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->filled('situation_matrimoniale')) {
            $query->where('situation_matrimoniale', $request->situation_matrimoniale);
        }

        return $query;
    }

    // --- MESSES EXPORTS ---

    public function exportMessesExcel(Request $request)
    {
        $type = $request->input('type', 'en_attente_confirmation');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        return Excel::download(new MessesExport($type, $start_date, $end_date), 'messes_'.$type.'_'.date('d-m-Y').'.xlsx');
    }

    public function exportMessesPdf(Request $request)
    {
        $type = $request->input('type', 'en_attente_confirmation');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = Auth::guard('paroisse')->user()->messes()->orderBy('created_at', 'desc');

        $title = 'Liste des Messes';

        switch ($type) {
            case 'en_attente_confirmation':
                $query->where('statut', 'en attente');
                $title = 'Messes en Attente de Confirmation';
                break;
            case 'a_celebrer':
                // Messes confirmées dont la date est passée ou aujourd'hui (mais pas encore marquées comme célébrées ?)
                // Le user a dit "a celebre" (to be celebrated?) or "has been celebrated"?
                // Usually "A célébrer" means future or today. "Historique" means past.
                // Let's assume "a_celebrer" matches the "A célébrées" menu item which usually is FUTURE confirmed.
                $query->where('statut', 'confirmee')
                    ->whereDate('date_souhaitee', '>=', now()->startOfDay());
                $title = 'Messes à Célébrer';
                break;
            case 'en_attente_celebration':
                // This sounds synonymous with A Célébrer, but the menu differentiates.
                // Sidebar says: "A célébrées" (route: demandes.messes.index) -> Confirmed future?
                // Sidebar says: "En attente célébrations" (route: demandes.messes.celebrated) -> This might be separate.
                // Let's look at DemandeController logic
                // celebrated() method uses: where('statut', 'confirmee')->where('date_souhaitee', '>=', now())
                // index() method (dashboard?) uses: where('statut', 'confirmee')->whereDate('date_souhaitee', '<=', $today) -> This is confusing.
                // Let's align with the controller logic.

                // Route "A célébrées" -> DemandeController@index -> where('statut', 'confirmee')->whereDate('date_souhaitee', '<=', $today)
                // Wait, if date is <= today, it SHOULD have been celebrated. Maybe "A célébrées" means "To be celebrated today or passed but not marked done"?
                // Actually DemandeController@index says "filteredMessess... whereDate ... <= today".

                // Route "En attente célébrations" -> DemandeController@celebrated -> where('statut', 'confirmee')->where('date_souhaitee', '>=', now())
                // This seems to be FUTURE.

                if ($type === 'a_platform_index') { // "A célébrées" from Controller index
                    $query->where('statut', 'confirmee')
                        ->whereDate('date_souhaitee', '<=', now()->startOfDay());
                    $title = 'Messes A Célébrer (Dates passées/aujourd\'hui)';
                } elseif ($type === 'en_attente_celebration') { // "En attente célébrations" from Controller celebrated
                    $query->where('statut', 'confirmee')
                        ->whereDate('date_souhaitee', '>=', now()->startOfDay());
                    $title = 'Messes en Attente de Célébration (Futur)';
                }
                break;
            case 'historique':
                // Route "Historique célébrations" -> OffrandeController@history usually.
                // Or maybe Messes marked as 'celebre'.
                // Let's include 'celebre', 'annulee', 'payee' (if applicable) etc.
                // user said "historique des celebration", likely 'celebre' status.
                $query->where('statut', 'celebre'); // or whereNotIn(['en attente', 'confirmee'])
                $title = 'Historique des Célébrations';
                break;
        }

        // Re-apply logic based on exact switch to match Sidebar intents
        // 1. En attente confirmation -> status 'en attente'
        // 2. A célébrer -> status 'confirmee' AND date <= today (based on DemandeController@index)
        // 3. En attente de celebration -> status 'confirmee' AND date >= today (based on DemandeController@celebrated)
        // 4. Historique -> status 'celebre' (based on generic logic, or whatever OffrandeController returns)

        // Let's refine the switch for the actual button calls:
        if ($type == 'en_attente_confirmation') {
            $query->where('statut', 'en attente');
            $title = 'Messes en Attente de Confirmation';
        } elseif ($type == 'a_celebrer') {
            // Matching DemandeController@index logic
            // "A célébrées" in sidebar
            $query->where('statut', 'confirmee')
                ->whereDate('date_souhaitee', '<=', now()->startOfDay());
            $title = 'Messes A Célébrer';
        } elseif ($type == 'en_attente_celebration') {
            // Matching DemandeController@celebrated logic
            // "En attente célébrations" in sidebar
            $query->where('statut', 'confirmee')
                ->whereDate('date_souhaitee', '>=', now()->startOfDay());
            $title = 'Messes en Attente de Célébration';
        } elseif ($type == 'historique') {
            // "Historique célébrations" in sidebar
            // Usually implies finished.
            $query->whereNotIn('statut', ['en attente', 'confirmee', 'en_attente_paiement']);
            $title = 'Historique des Célébrations';
        }

        if ($start_date) {
            $query->whereDate('date_souhaitee', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('date_souhaitee', '<=', $end_date);
        }

        $messes = $query->get();
        $totalMesses = $messes->count();
        $totalMontant = $messes->sum('montant_offrande');

        $pdf = Pdf::loadView('paroisse.exports.messe.pdf', compact('messes', 'title', 'totalMesses', 'totalMontant'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('messes_'.$type.'_'.date('d-m-Y').'.pdf');
    }
}
