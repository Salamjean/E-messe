<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paroissien extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_prenom',
        'date_naissance',
        'sexe',
        'situation_matrimoniale',
        'adresse',
        'statut_activite',
        'nom_paroisse',
        'telephone',
        'est_dans_mouvement',
        'nom_mouvement',
        'est_baptise',
        'date_bapteme',
        'photo',
        'user_id',
        'nom_paroisse_bapteme',
    ];

    protected $casts = [
        'est_dans_mouvement' => 'boolean',
        'est_baptise' => 'boolean',
        'date_naissance' => 'date',
        'date_bapteme' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
