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
        $this->authorize('favorite', Movie::class);

        $user = User::find(Auth::id());
        $query = $user->favorites();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->string('search') . '%');
        }

        $sort = $request->string('sort');
        switch ($sort) {
            case 'year':
                $query->orderBy('year', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            case 'rating':
                $query
                    ->withCount([
                        'reactions as likes_count' => function ($q) {
                            $q->where('reaction', 'like');
                        },
                        'reactions as dislikes_count' => function ($q) {
                            $q->where('reaction', 'dislike');
                        },
                    ])
                    ->orderByRaw(
                        '(CASE WHEN (likes_count + dislikes_count) > 0 THEN (likes_count * 1.0 / (likes_count + dislikes_count)) ELSE 0 END) asc'
                    );
                break;
            case 'rating_desc':
                $query
                    ->withCount([
                        'reactions as likes_count' => function ($q) {
                            $q->where('reaction', 'like');
                        },
                        'reactions as dislikes_count' => function ($q) {
                            $q->where('reaction', 'dislike');
                        },
                    ])
                    ->orderByRaw(
                        '(CASE WHEN (likes_count + dislikes_count) > 0 THEN (likes_count * 1.0 / (likes_count + dislikes_count)) ELSE 0 END) desc'
                    );
                break;
            case 'title':
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $favoriteMovies = $query->paginate(8)->withQueryString();

        $favoriteMovies->load(['favorites' => function ($query) {
            $query->where('user_id', Auth::id());
        }]);

        return Inertia::render('Favorite/Index', [
            'favoriteMovies' => $favoriteMovies,
            'sort' => $sort
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrDestroy(StoreFavoriteRequest $request)
    {
        $this->authorize('unfavorite', Movie::class);
        
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
