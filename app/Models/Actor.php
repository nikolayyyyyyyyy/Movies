<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'profile_picture',
    ];
}
