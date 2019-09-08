<?php

namespace Ollieread\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ollieread\Articles\Models\Comment;

class CommentResponse extends Notification
{
    use Queueable;

    /**
     * @var \Ollieread\Articles\Models\Comment
     */
    private $comment;

    /**
     * Create a new notification instance.
     *
     * @param \Ollieread\Articles\Models\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('mail.comment-response.subject'))
            ->line(sprintf('There has been a response to your comment on %s by %s.', $this->comment->article->name, $this->comment->author->username))
            ->action('View Response', route('articles:article', $this->comment->article->slug) . '#comment-' . $this->comment->id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }
}
