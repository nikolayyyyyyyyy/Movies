<?php

namespace App\Http\Controllers;

use App\Events\RatingUpdatedEvent;
use Illuminate\Http\Request;
use App\Models\UserMovieReaction;
use Illuminate\Support\Facades\Auth;

class UserMovieReactionController extends Controller
{
    public function store(Request $request)
    {
        $isUserReacted = UserMovieReaction::where('user_id', Auth::user()->id)
            ->where('movie_id', $request->movie_id)
            ->first();

        if (!$isUserReacted) {
            UserMovieReaction::create([
                'user_id' => Auth::user()->id,
                'movie_id' => $request->movie_id,
                'reaction' => $request->reaction,
            ]);

            return redirect()->back();
        }

        if ($isUserReacted->reaction == 'like' && $request->reaction == 'like') {
            $isUserReacted->delete();
            return redirect()->back();
        }

        if ($isUserReacted->reaction == 'dislike' && $request->reaction == 'dislike') {
            $isUserReacted->delete();
            return redirect()->back();
        }

        $isUserReacted->update([
            'reaction' => $request->reaction,
        ]);

        broadcast(new RatingUpdatedEvent())->toOthers();
        return redirect()->back();
    }
}
