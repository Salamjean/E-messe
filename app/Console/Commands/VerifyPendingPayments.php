<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Paiement;
use App\Services\WaveService;

class VerifyPendingPayments extends Command
{
    protected $signature = 'payments:verify';
    protected $description = 'Vérifier les paiements en attente avec Wave';

    public function handle()
    {
        $waveService = new WaveService();
        $pendingPayments = Paiement::where('statut', 'en_attente')
            ->where('methode', 'wave')
            ->get();

        foreach ($pendingPayments as $payment) {
            $transaction = $waveService->verifyByMerchantReference($payment->reference);
            
            if ($transaction && $transaction['status'] === 'completed') {
                $payment->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                    'donnees_transaction' => json_encode($transaction),
                ]);
                
                $payment->messe->update([
                    'statut' => 'confirme'
                ]);
                
                $this->info("Paiement {$payment->reference} confirmé.");
            }
        }
        
        $this->info('Vérification des paiements terminée.');
    }
}