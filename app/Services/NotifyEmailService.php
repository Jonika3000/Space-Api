<?php

namespace App\Services;

use App\Models\Comment;
use App\Notifications\PostCommentLeftNotification;

class NotifyEmailService
{
    public function notifyEmailCommentedPostAuthor(Comment $comment)
    {
        $post = $comment->post;
        $postAuthor = $post->user;
        $postAuthor->notify(new PostCommentLeftNotification($comment));
    }
}
