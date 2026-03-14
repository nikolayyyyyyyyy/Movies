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

        return collect($actors)->map(function ($actor) {
            return [
                'id' => $actor->id,
                'name' => $actor->name,
                'profile_picture' => asset('storage/' . $actor->profile_picture),
            ];
        })->all();
    }
}
