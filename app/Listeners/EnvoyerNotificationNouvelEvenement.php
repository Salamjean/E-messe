<?php

namespace App\Listeners;

use App\Events\NouvelEvenementCree;
use App\Models\Favori;
use App\Notifications\NouvelEvenementNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class EnvoyerNotificationNouvelEvenement implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(NouvelEvenementCree $event)
    {
        $paroisseId = $event->event->created_by;

        // Récupère les utilisateurs qui ont cette paroisse comme favori
        $users = Favori::where('paroisse_id', $paroisseId)
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter(); // retire les nulls

        if ($users->isNotEmpty()) {
            Notification::send($users, new NouvelEvenementNotification($event->event));
        }
    }
}
