<?php

namespace App\Jobs;

use App\Enums\CommentStatusEnum;
use App\Models\Comment;
use App\Services\NotifyEmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CommentsCheckingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();

        try {
            $this->comment->status = CommentStatusEnum::OnChecking->value;
            $this->comment->save();

            if ($this->containsForbiddenWords($this->comment->content)) {
                $this->comment->delete();
            } else {
                $this->comment->status = CommentStatusEnum::Verified->value;
                $this->comment->save();
                $notifyEmailService = app(NotifyEmailService::class);
                $notifyEmailService->notifyEmailCommentedPostAuthor($this->comment);
            }
            if($this->comment->status = CommentStatusEnum::OnChecking->value) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error checking comment: ' . $e->getMessage());
        }

        return;
    }

    private function containsForbiddenWords(string $text): bool
    {
        $forbiddenWords = ['forbidden_word1', 'forbidden_word2'];

        foreach ($forbiddenWords as $word) {
            if (stripos($text, $word) !== false) {
                return true;
            }
        }

        return false;
    }
}
