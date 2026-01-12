<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'type_event',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'celebrant',
        'organisateur',
        'participation_frais',
        'statut',
        'image',
        'created_by',
        'paroisse_id',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'participation_frais' => 'decimal:2',
    ];

    /**
     * Relation vers la paroisse qui a créé l'événement
     */
    public function paroisse()
    {
        return $this->belongsTo(Paroisse::class, 'paroisse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute()
    {
        $value = $this->image;

        if (! $value) {
            return null;
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
