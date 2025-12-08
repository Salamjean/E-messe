<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = [
        'type',
        'icon',
        'title',
        'value',
        'subtitle',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope pour filtrer les infos actives
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
