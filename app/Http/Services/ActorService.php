<?php

namespace App\Http\Services;

use App\Models\Actor;

class ActorService
{
    public static function searchActors($search)
    {
        $actors = [];

        if ($search) {
            $actors = Actor::where('name', 'like', '%' . $search . '%')->get();
        } else {
            $actors = Actor::all();
        }

        return $actors;
    }
}
