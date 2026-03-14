<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'image',
        'iframe_url',
        'rating',
        'year',
        'duration',
    ];

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
