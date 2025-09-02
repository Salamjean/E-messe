<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParoisseRetrait extends Model
{
   use HasFactory;

    protected $fillable = [
        'paroisse_id',
        'montant',
        'methode',
        'numero_compte',
        'nom_titulaire',
        'statut',
        'informations_supplementaires',
        'reference',
        'traite_le'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'traite_le' => 'datetime'
    ];

    public function paroisse()
    {
        return $this->belongsTo(Paroisse::class);
    }

    // Méthodes pour les statuts
    public function isEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function isTraite()
    {
        return $this->statut === 'traite';
    }

    public function isRejete()
    {
        return $this->statut === 'rejete';
    }

    public function isComplete()
    {
        return $this->statut === 'complete';
    }
}
