<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Events\CommentCreated;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        DB::transaction(function () use ($request) {
            $comment = Comment::create([
                'user_id' => Auth::user()->id,
                'movie_id' => $request->movie_id,
                'comment' => $request->comment,
            ]);

            broadcast(new CommentCreated($comment))->toOthers();
        });

        return redirect()
            ->back();
    }
}
