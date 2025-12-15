<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportHour extends Model
{
    protected $fillable = [
        'type',
        'title',
        'schedule',
        'note',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope pour filtrer les horaires actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
