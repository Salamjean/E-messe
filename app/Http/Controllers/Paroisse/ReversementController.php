<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReversementController extends Controller
{
    protected $cinetpay;

    public function __construct(CinetPayService $cinetpay)
    {
        $this->cinetpay = $cinetpay;
    }

    // Fonction utilisée par votre JS : route('reversement.store')
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string', // wave, orange_money, etc.
            'prefix' => 'required|string',
            'telephone' => 'required|string',
        ]);

        $paroisse = Auth::guard('paroisse')->user();

        // Méthode fictive pour calculer le solde (à adapter selon votre logique)
        // $soldeDisponible = $this->calculerSolde($paroisse->id);
        // Pour l'exemple, supposons que vous avez cette méthode dans un Trait ou Service
        $soldeDisponible = 1000000; // Exemple

        if ($request->montant > $soldeDisponible) {
            return response()->json([
                'message' => 'Solde insuffisant.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // 2. Création de la transaction en base de données (Statut En Attente)
            $reference = 'REV-'.time().'-'.$paroisse->id;

            $retrait = new ParoisseRetrait;
            $retrait->paroisse_id = $paroisse->id;
            $retrait->montant = $request->montant;
            $retrait->methode = $request->methode;
            $retrait->numero_compte = $request->prefix.$request->telephone; // On stocke le numéro complet
            $retrait->nom_banque = $request->methode; // Ex: wave
            $retrait->nom_titulaire = $paroisse->name; // Ou un champ nom destinataire si ajouté au form
            $retrait->reference = $reference;
            $retrait->statut = 'traitement_cours'; // Statut intermédiaire
            $retrait->save();

            // 3. Appel à l'API CinetPay via le Service
            $result = $this->cinetpay->sendMoney(
                $request->prefix,
                $request->telephone,
                $request->montant,
                $reference,
                $paroisse->name // Nom utilisé pour créer le contact
            );

            Log::info('Réponse brute reçue de CinetPay Service (ReversementController):', ['result' => $result]);

            // 4. Analyse de la réponse CinetPay
            if (isset($result['code']) && ($result['code'] == 0 || $result['code'] == '0' || $result['code'] == '201')) {
                // CinetPay a accepté la demande (elle est en cours chez l'opérateur)
                Log::info('Transfert CinetPay accepté (ReversementController).');

                // Optionnel : Déduire le solde ici ou attendre le callback
                // $this->deduireSolde($paroisse->id, $request->montant);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Transfert initié avec succès via CinetPay. Statut: '.($result['msg'] ?? 'En cours'),
                ]);
            } else {
                // Echec immédiat (Ex: Solde CinetPay insuffisant, Numéro invalide)
                DB::rollBack(); // On annule l'enregistrement en base

                $errorMsg = $result['msg'] ?? 'Erreur inconnue chez l\'opérateur';

                return response()->json([
                    'message' => 'Echec du transfert : '.$errorMsg,
                ], 422);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur Reversement Controller: '.$e->getMessage());

            return response()->json([
                'message' => 'Une erreur interne est survenue.',
            ], 500);
        }
    }

    public function notifyCinetPay(Request $request)
    {
        // CinetPay envoie des données POST (client_transaction_id, status, etc.)
        // Loggez la requête pour déboguer au début
        Log::info('CinetPay Callback (ReversementController):', [
            'data' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        $client_transaction_id = $request->input('client_transaction_id');
        // Statut possible : 1 (Succès), 2 (Echec), 00 (Succès)
        $status_cinetpay = $request->input('status');
        Log::info('Traitement notification (ReversementController) pour REF: '.$client_transaction_id.' Statut: '.$status_cinetpay);
        // Retrouver la transaction
        $retrait = ParoisseRetrait::where('reference', $client_transaction_id)->first();

        if ($retrait) {
            if ($status_cinetpay == 1 || $status_cinetpay == 'ACCEPTED') { // Vérifiez le code exact de succès
                $retrait->statut = 'valide';
                // Si le solde n'a pas été déduit à l'initiation, faites-le ici
            } else {
                $retrait->statut = 'echoue';
                // Si le solde a été déduit, remboursez-le ici
            }
            $retrait->save();
        }

        return response()->json(['code' => 200, 'message' => 'OK']);
    }
}
