<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $favoriteMovies = $user->favorites;

        if ($request->has('search') && !empty($request->search)) {
            $favoriteMovies = $favoriteMovies->filter(function (Movie $movie) use ($request) {
                return strtolower($movie->title) == strtolower($request->search);
            });
        }

        collect($favoriteMovies)->each(function ($favorite) {
            $favorite->load(['favorites' => function ($query) {
                $query->where('user_id', Auth::user()->id);
            }]);
        });

        return Inertia::render('Favorite/Index', [
            'favoriteMovies' => $favoriteMovies->values()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrDestroy(StoreFavoriteRequest $request)
    {
        $user = User::find(Auth::user()->id);
        if ($user->favorites()->where('movie_id', $request->movie_id)->exists()) {
            $user->favorites()->detach($request->movie_id);
        } else {
            $user->favorites()->attach($request->movie_id);
        }

        return redirect()
            ->back();
    }
}
