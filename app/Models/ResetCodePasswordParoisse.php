<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetCodePasswordParoisse extends Model
{
    protected $fillable = ['code', 'email'];
}
