<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'actif',
        'archived_at',
    ];

    public function messess()
    {
        return $this->hasMany(Messe::class);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Scope pour les utilisateurs archivés
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    // Scope pour les utilisateurs non archivés
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    // Méthode pour archiver un utilisateur
    public function archive()
    {
        $this->update(['archived_at' => now()]);
    }

    // Méthode pour désarchiver un utilisateur
    public function unarchive()
    {
        $this->update(['archived_at' => null]);
    }

    // Vérifier si l'utilisateur est archivé
    public function isArchived()
    {
        return !is_null($this->archived_at);
    }
}
