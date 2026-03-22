<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private Comment $comment;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('movie.' . $this->comment->movie_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'comment.created';
    }

    public function broadcastWith(): array
    {
        $this->comment->loadMissing('user');

        return [
            'comment' => $this->comment->toArray(),
        ];
    }
}
