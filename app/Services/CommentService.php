<?php

namespace App\Services;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\PostCommentLeftNotification;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function createComment(StoreCommentRequest $request)
    {
        $comment = Auth::user()->comments()->create($request->validated());
        $this->notifyEmailPostAuthor($comment);

        return $comment;
    }

    public function notifyEmailPostAuthor(Comment $comment)
    {
        $post = $comment->post;
        $postAuthor = $post->user;
        $postAuthor->notify(new PostCommentLeftNotification($comment));
    }
}
