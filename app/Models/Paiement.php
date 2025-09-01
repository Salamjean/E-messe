<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'messe_id',
        'user_id',
        'reference',
        'montant',
        'devise',
        'methode',
        'statut',
        'transaction_id',
        'donnees_transaction',
        'date_paiement'
    ];

    protected $casts = [
        'donnees_transaction' => 'array',
        'date_paiement' => 'datetime',
        'montant' => 'decimal:2'
    ];

    public function messe()
    {
        return $this->belongsTo(Messe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}