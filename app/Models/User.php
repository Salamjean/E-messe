<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs pouvant être remplis en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'indicatif',
        'contact',
        'google_id',
        'apple_id',
        // 'commune',
        // 'CMU',
        'profile_picture',
        'actif',
        'emailNotif',
        'civilite',
        'smsNotif',
        'pushNotif',
        'fcm_token',
    ];

    /**
     * Les attributs à masquer lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être typés (cast).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Laravel 12 : hash auto lors de la sauvegarde
        ];
    }

    /**
     * Relation : un utilisateur peut avoir plusieurs messes.
     */
    public function messes()
    {
        return $this->hasMany(Messe::class);
    }

    public function favoris()
    {
        return $this->hasMany(\App\Models\Favori::class);
    }

    /**
     * Scope : récupérer uniquement les utilisateurs archivés.
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope : récupérer uniquement les utilisateurs actifs (non archivés).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Archiver un utilisateur.
     */
    public function archive()
    {
        $this->update(['archived_at' => now()]);
    }

    /**
     * Désarchiver un utilisateur.
     */
    public function unarchive()
    {
        $this->update(['archived_at' => null]);
    }

    /**
     * Vérifie si l'utilisateur est archivé.
     */
    public function isArchived(): bool
    {
        return ! is_null($this->archived_at);
    }

    public function versets()
    {
        return $this->belongsToMany(Verset::class, 'user_verset');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * Get the profile picture URL.
     */
    public function getProfilePictureUrlAttribute()
    {
        $value = $this->profile_picture;

        if (! $value) {
            return 'https://ui-avatars.com/api/?name='.urlencode($this->name ?: 'User').'&background=random&color=fff';
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Check if file exists in public directory
        if (file_exists(public_path($value))) {
            return asset($value);
        }

        return Storage::url($value);
    }
}
