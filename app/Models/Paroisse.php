<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Paroisse extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guard = 'paroisse';
    protected $fillable = [
        'name',
        'email',
        'contact',
        'password',
        'profile_picture',
        'localisation',
    ];

    public function messess()
    {
        return $this->hasMany(Messe::class);
    }

    public function solde()
    {
        return $this->hasOne(ParoisseSolde::class);
    }

    public function retraits()
    {
        return $this->hasMany(ParoisseRetrait::class);
    }

    // Méthode pour récupérer ou créer le solde
    public function getSolde()
    {
        return $this->solde()->firstOrCreate([], ['solde' => 0]);
    }

    // Méthode pour mettre à jour le solde
    public function updateSolde($montant)
    {
        $solde = $this->getSolde();
        $solde->solde += $montant;
        $solde->save();
        
        return $solde;
    }
}
