<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reversement extends Model
{
    use HasFactory;

    // Nom de la table (optionnel si convention Laravel respectée)
    protected $table = 'reversements';

    // Champs remplissables via assignation de masse
    protected $fillable = [
        'reference',
        'numero_destinataire',
        'prefix_pays',
        'montant',
        'statut',
        'cinetpay_transfer_id',
        'donnees_api'
    ];

    // Cast pour les champs JSON et autres types spécifiques
    protected $casts = [
        'donnees_api' => 'array',
        'montant' => 'decimal:2',
    ];

    /**
     * Génération automatique de la référence si non fournie
     */
    protected static function booted()
    {
        static::creating(function ($reversement) {
            if (empty($reversement->reference)) {
                $reversement->reference = 'REV-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Accessor pour afficher le numéro complet avec indicatif pays
     */
    public function getNumeroCompletAttribute(): string
    {
        return '(+' . $this->prefix_pays . ') ' . $this->numero_destinataire;
    }

    /**
     * Scope pour filtrer les reversements réussis
     */
    public function scopeSuccess($query)
    {
        return $query->where('statut', 'success');
    }

    /**
     * Scope pour filtrer les reversements en attente
     */
    public function scopePending($query)
    {
        return $query->where('statut', 'pending');
    }

    /**
     * Scope pour filtrer les reversements échoués
     */
    public function scopeFailed($query)
    {
        return $query->where('statut', 'failed');
    }
}
