<?php

namespace Ollieread\Articles\Observers;

use Ollieread\Articles\Models\Comment;
use Ollieread\Users\Notifications\CommentResponse;
use Ollieread\Users\Notifications\CommentResponseOllie;
use Ollieread\Users\Operations\GetUser;

class CommentObserver
{
    /**
     * Handle the article "created" event.
     *
     * @param \Ollieread\Articles\Models\Comment $comment
     *
     * @return void
     */
    public function created(Comment $comment): void
    {
        $parent = $comment->parent;

        if ($parent && $parent->author_id !== $comment->author_id) {
            $parent->author->notify(new CommentResponse($comment));
        }

        if (! $parent) {
            (new GetUser)
                ->setUsername('ollieread')
                // These two calls are here to guarantee I always get the emails...
                ->setActiveOnly(false)
                // ...even if for some reason I'm inactive or unverified
                ->setVerifiedOnly(false)
                ->perform()
                ->notify(new CommentResponseOllie);
        }
    }
}
