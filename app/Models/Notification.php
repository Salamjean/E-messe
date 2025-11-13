<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class Notification extends BaseDatabaseNotification
{
    protected $fillable = [
        'id',
        'type',
        'title',
        'body',
        'messe_id',
        'notifiable_type',
        'notifiable_id',
        'read_at',
        'data',    
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

   /**
     * La "méthode de démarrage" du modèle.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            // 1. Récupérer les données dans une variable locale.
            $data = $notification->data;

            if (is_array($data)) {
                // 2. Assigner les valeurs aux colonnes dédiées.
                // On ajoute la ligne pour le 'type' ici.
                $notification->type = $data['type'] ?? $notification->type; // Remplace le nom de la classe par le type personnalisé
                $notification->title = $data['title'] ?? null;
                $notification->body = $data['body'] ?? null;
                $notification->messe_id = $data['messe_id'] ?? null;

                // 3. Modifier la variable locale en retirant les clés déjà mappées.
                unset(
                    $data['type'], // On ajoute le 'type' à la liste des clés à retirer
                    $data['title'],
                    $data['body'],
                    $data['messe_id']
                );

                // 4. Réassigner le tableau nettoyé à la propriété 'data' du modèle.
                $notification->data = $data;
            }
        });
    }
}
