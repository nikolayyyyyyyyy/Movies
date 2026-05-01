<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('movie.' . $this->comment->movie_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'comment.updated';
    }

    public function broadcastWith(): array
    {
        $this->comment->loadMissing('user');

        return [
            'comment' => $this->comment->toArray(),
        ];
    }
}
