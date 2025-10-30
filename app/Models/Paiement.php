<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *     schema="Paiement",
 *     title="Paiement",
 *     description="Représente un paiement pour une messe",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="messe_id", type="integer", example=10),
 *     @OA\Property(property="user_id", type="integer", example=10),
 *     @OA\Property(property="reference", type="string", example="MESSE_API_1698741234_1"),
 *     @OA\Property(property="montant", type="number", format="float", example=5100.00),
 *     @OA\Property(property="devise", type="string", example="XOF"),
 *     @OA\Property(property="methode", type="string", example="wave"),
 *     @OA\Property(property="statut", type="string", example="paye"),
 *     @OA\Property(property="transaction_id", type="string", example="TX_5f3d2a1b8c"),
 *     @OA\Property(property="donnees_transaction", type="object", example={"message":"Paiement réussi"}),
 *     @OA\Property(property="date_paiement", type="string", format="date-time", example="2025-10-30T10:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */


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