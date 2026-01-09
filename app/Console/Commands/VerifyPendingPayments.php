<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Paiement;
use App\Services\CinetPayService;

class VerifyPendingPayments extends Command
{
    protected $signature = 'payments:verify';
    protected $description = 'Vérifier les paiements en attente avec CinetPay';

    public function handle(CinetPayService $cinetPayService)
    {
        $pendingPayments = Paiement::where('statut', 'en_attente')
            ->get();

        foreach ($pendingPayments as $payment) {
            $response = $cinetPayService->checkStatus($payment->reference);
            
            if (isset($response['code']) && $response['code'] === '00') {
                $payment->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                    'donnees_transaction' => json_encode($response['data']),
                    'operateur' => $response['data']['payment_method'] ?? $payment->operateur
                ]);
                
                if ($payment->messe) {
                    $payment->messe->update([
                        'statut' => 'en attente'
                    ]);
                }
                
                $this->info("Paiement {$payment->reference} confirmé via CinetPay.");
            }
        }
        
        $this->info('Vérification des paiements terminée.');
    }
}