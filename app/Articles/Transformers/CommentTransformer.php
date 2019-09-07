<?php

namespace Ollieread\Articles\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Ollieread\Articles\Models\Comment;
use Ollieread\Articles\Support\Markdown;
use Ollieread\Users\Transformers\UserTransformer;

class CommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'replies',
        'author',
    ];

    public function includeAuthor(Comment $comment): Item
    {
        return $this->item($comment->author, new UserTransformer);
    }

    public function includeReplies(Comment $comment): Collection
    {
        return $this->collection($comment->replies, new self);
    }

    public function transform(Comment $comment): array
    {
        return [
            'id'          => $comment->id,
            'comment'     => Markdown::parseComment($comment->comment),
            'reply_count' => $comment->reply_count ?? 0,
            'created_at'  => $comment->created_at->format('H:ia \o\n jS F Y'),
        ];
    }
}
