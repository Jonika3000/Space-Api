<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostCommentLeftNotification extends Notification
{
    use Queueable;
    private $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $commentText = $this->comment->content;
        $commentAuthor = $this->comment->author_name;
        $postTitle = $this->comment->post->title;
        $postUrl = env('FRONTEND_URL') . '/posts/' . $this->comment->post->id;

        return (new MailMessage)
            ->subject('New Comment on Your Post')
            ->greeting('Hello,')
            ->line("A new comment was left on your post: \"{$postTitle}\".")
            ->line("Comment by {$commentAuthor}:")
            ->line("\"{$commentText}\"")
            ->action('View Comment', $postUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
