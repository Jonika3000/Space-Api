<?php

namespace App\Services;

use App\Enums\CommentStatusEnum;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Jobs\CommentsCheckingJob;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function createComment(array $commentData)
    {
        $commentData['status'] = CommentStatusEnum::NotVerified->value;
        $comment = Auth::user()->comments()->create($commentData);
        CommentsCheckingJob::dispatch($comment);

        return $comment;
    }


}
