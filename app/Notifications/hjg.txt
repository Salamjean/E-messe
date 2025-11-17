<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class NouveauEvenementParoisseNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Un seul canal Laravel → database
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Notification enregistrée en DB + envoi FCM en data-only
     */
    public function toDatabase($notifiable)
    {
        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        $title = 'Nouvel événement créé 🎉';
        $body = "« {$paroisseName} que vous suivez organise un événement : {$this->event->titre} » "
               . "qui aura lieu le " . $this->event->date_debut->format('d/m/Y') . ". Venez nombreux !";

        // 🔹 Envoi FCM (DATA ONLY) si token disponible
        if (!empty($notifiable->fcm_token)) {
            try {

                Http::withHeaders([
                    'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [

                    'to' => $notifiable->fcm_token,

                    // ⚠️ DATA ONLY : PAS DE BLOC "notification"
                    'data' => [
                        'title' => 'Nouvel événement paroissial 🎊',
                        'body'  => "« {$paroisseName} » organise l'événement « {$this->event->titre} ». ",

                        // Données supplémentaires
                        'type' => 'nouveau_evenement',
                        'evenement_id' => $this->event->id,
                        'paroisse_id' => $this->event->created_by,
                    ],

                ]);

            } catch (\Exception $e) {
                \Log::error("Échec envoi FCM (event #{$this->event->id}) : " . $e->getMessage());
            }
        }

        return [
            'type' => 'nouveau_evenement',
            'title' => $title,
            'body' => $body,
            'evenement_id' => $this->event->id,
            'paroisse_id' => $this->event->created_by,
        ];
    }
}
