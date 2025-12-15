<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'location',
        'message',
        'rating',
        'display_on',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];

    // Scope pour filtrer les témoignages actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour filtrer par page
    public function scopeForHome($query)
    {
        return $query->where(function($q) {
            $q->where('display_on', 'home')
              ->orWhere('display_on', 'both');
        });
    }

    public function scopeForAvantages($query)
    {
        return $query->where(function($q) {
            $q->where('display_on', 'avantages')
              ->orWhere('display_on', 'both');
        });
    }
}
