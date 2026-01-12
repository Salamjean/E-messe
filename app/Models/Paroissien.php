<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Get the photo URL.
     */
    public function getPhotoUrlAttribute()
    {
        $value = $this->photo;
        
        if (!$value) {
            return 'https://via.placeholder.com/200';
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        if (file_exists(public_path($value))) {
            return asset($value);
        }

        return Storage::url($value);
    }
}
