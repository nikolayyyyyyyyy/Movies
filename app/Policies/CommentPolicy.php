<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\RoleEnum;

class CommentPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id == $user->id || $user->role_id == RoleEnum::Admin->value;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id == $user->id || $user->role_id == RoleEnum::Admin->value;
    }
}
