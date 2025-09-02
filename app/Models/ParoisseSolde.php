<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParoisseSolde extends Model
{
   use HasFactory;

    protected $fillable = [
        'paroisse_id',
        'solde'
    ];

    protected $casts = [
        'solde' => 'decimal:2'
    ];

    public function paroisse()
    {
        return $this->belongsTo(Paroisse::class);
    }
}
