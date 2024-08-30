<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id ||
            $user->role === RoleEnum::ADMIN->value ||
            $user->role === RoleEnum::EDITOR->value;
    }

    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id ||
            $user->role === RoleEnum::ADMIN->value ||
            $user->role === RoleEnum::EDITOR->value;
    }
}
