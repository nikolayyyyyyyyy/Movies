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

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $categoryId = $request->category;

    $movies = \App\Models\Movie::with(['categories', 'actors'])
        ->when($categoryId, function ($query) use ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        })
        ->paginate(10)
        ->withQueryString();

    return \Inertia\Inertia::render('Movies/Index', [
        'movies' => $movies,
        'filters' => [
            'category' => $categoryId,
        ],
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
                'description' => $request->description,
                'image' => $request->hasFile('image') ? $request->file('image')->store('movies_images', 'public') : null,
                'iframe_url' => $request->iframe_url,
                'rating' => $request->rating,
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
        $movie = Movie::with('categories', 'actors')->find($movie->id);
        return Inertia::render('Movies/Show', [
            'movie' => $movie,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
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
        //
    }
}
