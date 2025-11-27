<?php

namespace App\Http\Controllers\Paroisse\Paiement;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParoissePaiement extends Controller
{
    public function index()
    {
        $paroisse = Auth::guard('paroisse')->user();
    
        // Récupérer les retraits de la paroisse
        $retraits = ParoisseRetrait::where('paroisse_id', $paroisse->id)
                    ->where('statut','en_attente')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
         $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');
        
        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut','!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');
        
        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits  ;
        
        return view('paroisse.retrait.index', compact('retraits', 'soldeDisponible'));
    }
    
    public function history()
    {
        $paroisse = Auth::guard('paroisse')->user();
    
        // Récupérer les retraits de la paroisse
        $retraits = ParoisseRetrait::where('paroisse_id', $paroisse->id)
                    ->where('statut','!=','en_attente')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
         $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');
        
        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut','!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');
        
        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits  ;
        
        return view('paroisse.retrait.history', compact('retraits', 'soldeDisponible'));
    }

    public function create(){
        $paroisse = Auth::guard('paroisse')->user();
        // Calculer le montant total des paiements pour cette paroisse
        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');
        
        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut','!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');
        
        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits  ;

        return view('paroisse.retrait.create',compact('soldeDisponible'));
    }

    public function requestRetrait(Request $request)
    {
        $rules = [
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string',
            'numero_compte' => 'required|string',
            'nom_titulaire' => 'required|string',
        ];
        
        // Ajouter la règle conditionnelle pour nom_banque
        if ($request->methode === 'virement_bancaire') {
            $rules['nom_banque'] = 'required|string';
        }
        
        $request->validate($rules);
        
        $paroisse = Auth::guard('paroisse')->user();
        
        // Calculer le solde actuel (total des paiements)
        $solde = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'payé')
            ->sum('paiements.montant');
        
        if ($request->montant > $solde) {
            return back()->with('error', 'Le montant demandé dépasse votre solde disponible.');
        }
        
        // Créer la demande de retrait
        $retrait = new ParoisseRetrait();
        $retrait->paroisse_id = $paroisse->id;
        $retrait->montant = $request->montant;
        $retrait->methode = $request->methode;
        $retrait->numero_compte = $request->numero_compte;
        $retrait->nom_titulaire = $request->nom_titulaire;
        $retrait->nom_banque = $request->nom_banque; // Nouveau champ
        $retrait->reference = 'RET' . time() . $paroisse->id;
        $retrait->statut = 'en_attente';
        $retrait->save();
        
        return redirect()->route('paroisse.retraits')->with('success', 'Votre demande de retrait a été envoyée avec succès.');
    }

    public function annuler($id)
    {
        $retrait = ParoisseRetrait::findOrFail($id);
        
        // Vérifier que le retrait appartient à la paroisse connectée
        if ($retrait->paroisse_id !== Auth::guard('paroisse')->id()) {
            return back()->with('error', 'Action non autorisée.');
        }
        
        // Vérifier que le retrait est encore en attente
        if ($retrait->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les retraits en attente peuvent être annulés.');
        }
        
        // Annuler le retrait
        $retrait->statut = 'rejete';
        $retrait->save();
        
        return back()->with('success', 'La demande de retrait a été annulée avec succès.');
    }


    public function list_reversement()
    {
        return view('paroisse.reversement.index');
    }

    // 2. Données pour DataTables (JSON)
    public function getData()
    {
        $reversements = Reversement::orderBy('created_at', 'desc')->get();

        return datatables()->of($reversements)
            ->editColumn('created_at', function($row){
                return $row->created_at->format('d/m/Y H:i');
            })
            ->editColumn('montant', function($row){
                return number_format($row->montant, 0, ',', ' ') . ' FCFA';
            })
            ->editColumn('statut', function($row){
                if($row->statut == 'success') return '<span class="badge bg-success">Succès</span>';
                if($row->statut == 'failed') return '<span class="badge bg-danger">Échec</span>';
                return '<span class="badge bg-warning">En attente</span>';
            })
            ->rawColumns(['statut'])
            ->make(true);
    }

    // 3. Logique du Reversement (Transfert)
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'montant' => 'required|numeric|min:100',
            'telephone' => 'required|numeric', // Sans indicatif, ex: 0707070707
            'prefix' => 'required' // Ex: 225
        ]);

        // Génération référence unique
        $reference = 'REV_' . time() . '_' . rand(1000, 9999);

        // Enregistrement local (État initial)
        $reversement = Reversement::create([
            'reference' => $reference,
            'numero_destinataire' => $request->telephone,
            'prefix_pays' => $request->prefix,
            'montant' => $request->montant,
            'statut' => 'pending'
        ]);

        try {
            // URL API Transfert CinetPay (Vérifiez la documentation pour la V1 ou V2 Transfert)
            // Pour l'exemple, j'utilise l'endpoint standard de demande de transfert
            $apiUrl = 'https://client.cinetpay.com/v1/transfer/money/send/contact';
            
            // NOTE: Pour les transferts, il faut souvent s'authentifier pour avoir un TOKEN d'abord.
            // Si votre API Key suffit (dépend de votre config CinetPay), voici la structure :
            
            $payload = [
                'apikey' => env('CINETPAY_API_KEY'),
                'site_id' => env('CINETPAY_SITE_ID'), // Ou password selon le cas
                'prefix' => $request->prefix,
                'phone' => $request->telephone,
                'amount' => $request->montant,
                'client_transaction_id' => $reference,
                'notify_url' => route('cinetpay.notify'), // Route à définir
                // 'treatment_status' => 'instant' // Optionnel
            ];

            // Appel API
            $response = Http::withOptions(['verify' => false])->post($apiUrl, $payload);
            $result = $response->json();

            // Mise à jour avec la réponse
            $reversement->donnees_api = $result;
            $reversement->save();

            // Analyse de la réponse (Adaptez selon la réponse réelle de l'API Transfert)
            if ($response->successful() && isset($result['code']) && $result['code'] == '0') {
                
                $reversement->update([
                    'statut' => 'success', // Ou 'pending' si le traitement est asynchrone
                    'cinetpay_transfer_id' => $result['data']['transfer_id'] ?? null
                ]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Reversement initié avec succès.'
                ]);
            } else {
                // Échec API
                $reversement->update(['statut' => 'failed']);
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Erreur CinetPay: ' . ($result['message'] ?? 'Erreur inconnue'),
                    'details' => $result
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error("Erreur Reversement: " . $e->getMessage());
            $reversement->update(['statut' => 'failed', 'donnees_api' => ['error' => $e->getMessage()]]);
            
            return response()->json(['success' => false, 'message' => 'Erreur serveur interne.'], 500);
        }
    }


}
