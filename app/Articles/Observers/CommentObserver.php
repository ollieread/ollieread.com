<?php

namespace Ollieread\Articles\Observers;

use Ollieread\Articles\Models\Comment;
use Ollieread\Users\Notifications\CommentResponse;

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

        if ($parent) {
            $parent->author->notify(new CommentResponse($comment));
        }
    }
}
