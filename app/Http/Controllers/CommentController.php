<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Events\CommentDeleted;
use App\Events\CommentCreated;
use App\Events\CommentUpdated;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

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

    public function destroy(Comment $comment)
    {
        broadcast(new CommentDeleted($comment))->toOthers();
        $comment->delete();
        return redirect()
            ->back();
    }

    public function update(Comment $comment, Request $request)
    {
        $comment->update([
            'comment' => $request->comment,
        ]);

        broadcast(new CommentUpdated($comment))->toOthers();
        return redirect()
            ->back();
    }
}
