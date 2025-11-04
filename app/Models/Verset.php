<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Verset extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'reference',
        'texte',
    ];
}
