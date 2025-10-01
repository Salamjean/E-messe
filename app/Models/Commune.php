<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    protected $fillable = ['ville_id', 'nom_commune'];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function paroisses()
    {
        return $this->hasMany(Paroisse::class);
    }
}