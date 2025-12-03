<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @OA\Schema(
 *     schema="Paroisse",
 *     type="object",
 *     title="Paroisse",
 *     description="Informations de base d'une paroisse",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Paroisse Saint Michel"),
 *     @OA\Property(property="email", type="string", example="saintmichel@eglise.ci"),
 *     @OA\Property(property="contact", type="string", example="+2250700000000"),
 *     @OA\Property(property="commune", type="string", example="Yopougon"),
 *     @OA\Property(property="ville", type="string", example="Abidjan"),
 *     @OA\Property(property="montant_total_messes", type="number", example=45000),
 *     @OA\Property(property="montant_moyen_messes", type="number", example=9000),
 *     @OA\Property(property="is_favori", type="boolean", example=true)
 * )
 */
class Paroisse extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'paroisse';

    protected $fillable = [
        'name',
        'email',
        'contact',
        'tel',
        'password',
        'profile_picture',
        'commune_id',
        'paroisse_id',
    ];

    // NOUVELLE RELATION
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function messes()
    {
        return $this->hasMany(Messe::class);
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class);
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
