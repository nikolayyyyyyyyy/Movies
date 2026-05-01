<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\ActorService;
use App\Http\Requests\StoreMovieRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Movie::class, 'movie');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Movie::query()
            ->with(['categories', 'actors']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->string('search') . '%');
        }

        $sort = $request->string('sort')->toString() ?? 'title';
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
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $movies = $query->paginate(8)->withQueryString();

        $movies->load(['favorites' => function ($query) {
            $query->where('user_id', Auth::id());
        }]);

        return Inertia::render('Movies/Index', [
            'movies' => $movies,
            'sort' => $sort
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return Inertia::render('Movies/Create', [
            'categories' => $categories ?? [],
            'actors' => ActorService::searchActors($request->search ?? null) ?? [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): RedirectResponse
    {
        return DB::transaction(function () use ($request) {
            $movie = Movie::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'image' => $request->hasFile('image') ? $request->file('image')->store('movies_images', 'public') : null,
                'iframe_url' => $request->iframe_url,
                'year' => $request->year,
                'duration' => $request->duration,
            ]);

            $movie->categories()->attach($request->categories ?? []);

            return redirect()
                ->route('movies.show', ['movie' => $movie->id]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        $movie = Movie::query()
            ->with([
                'categories',
                'actors',
                'user',
                'comments.user',
                'reactions' => function ($query) {
                    $query->where('user_id', Auth::id());
                }
            ])
            ->withCount([
                'reactions as likes_count' => function ($query) {
                    $query->where('reaction', 'like');
                },
                'reactions as dislikes_count' => function ($query) {
                    $query->where('reaction', 'dislike');
                },
            ])
            ->findOrFail($movie->id);

        $total = $movie->likes_count + $movie->dislikes_count;
        $movie->rating = $total > 0
            ? round(($movie->likes_count / $total) * 10, 1) // 0..10
            : 0.0;

        return Inertia::render('Movies/Show', [
            'movie' => $movie,
            'rating' => $movie->rating
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        dd($movie->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return redirect()->route('movies.index');
    }
}
