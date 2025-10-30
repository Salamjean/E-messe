<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Favori",
 *     type="object",
 *     title="Favori",
 *     description="Modèle Favori représentant une paroisse enregistrée comme favori par un utilisateur",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="paroisse_id", type="integer", example=5)
 * )
 */

class Favori extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paroisse_id',
    ];

    public function paroisse()
    {
        return $this->belongsTo(Paroisse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
