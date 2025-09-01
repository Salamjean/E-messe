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
}
