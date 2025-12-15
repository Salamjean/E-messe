<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'type_event',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'celebrant',
        'organisateur',
        'participation_frais',
        'statut',
        'image',
        'created_by',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'participation_frais' => 'decimal:2',
    ];

    /**
     * Relation vers la paroisse qui a créé l'événement
     * Limite les champs récupérés pour optimiser la requête
     */
    public function paroisse()
    {
        return $this->belongsTo(Paroisse::class, 'created_by');
    }
}
