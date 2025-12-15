<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDelete extends Model
{
    protected $table = 'user_deletes';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'user_name',
        'email',
        'contact',
        'profile_picture',
        'additional_data',
        'deleted_at'
    ];
}
