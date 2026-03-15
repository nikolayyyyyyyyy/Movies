<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Actor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'profile_picture',
    ];

    protected function profilePicture(): Attribute
    {
        return Attribute::make(
            get: fn($value) => asset('storage/' . $value),
        );
    }
}
