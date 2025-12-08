<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParishSuccessStory extends Model
{
    protected $fillable = [
        'name',
        'location',
        'participation_increase',
        'description',
        'active_users',
        'masses_reserved',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'active_users' => 'integer',
        'masses_reserved' => 'integer',
    ];

    // Scope pour filtrer les histoires actives
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
