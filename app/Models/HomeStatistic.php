<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeStatistic extends Model
{
    protected $fillable = [
        'parishes_count',
        'users_count',
        'availability',
    ];
}
