<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvantageImpact extends Model
{
    protected $fillable = [
        'value',
        'label',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Scope pour filtrer les impacts actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour trier par ordre
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
