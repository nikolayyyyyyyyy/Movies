<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Request $request)
    {
        $query = $category->movies();

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
                $query->orderBy('title', 'asc');
                break;
        }

        $movies = $query->paginate(8)->withQueryString();

        return Inertia::render('Category/Index', [
            'movies' => $movies,
            'category' => $category,
            'sort' => $sort
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
