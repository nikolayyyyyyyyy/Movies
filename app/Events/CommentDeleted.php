<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $movieId;
    private int $commentId;

    public function __construct(Comment $comment)
    {
        $this->movieId = (int) $comment->movie_id;
        $this->commentId = (int) $comment->id;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('movie.' . $this->movieId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'comment.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'comment_id' => $this->commentId,
        ];
    }
}
