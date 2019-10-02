<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Models\Comment;

class GetArticleComments
{
    /**
     * @var \Ollieread\Articles\Models\Article
     */
    private $article;

    /**
     * @var bool
     */
    private $hierarchy = false;

    public function perform(): Collection
    {
        $comments = Comment::query()->whereHas('article', function (Builder $query) {
            $query->where('articles.id', '=', $this->article->id);
        })->orderBy('created_at')->get();

        if ($this->hierarchy) {
            $replyCountClosure = static function ($carry, Comment $comment) use (&$replyCountClosure) {
                if ($comment->replies->count()) {
                    $comment->reply_count = $comment->replies->count() + $comment->replies->reduce($replyCountClosure);
                    $carry                += $comment->reply_count;
                }

                return (int) $carry;
            };

            $comments = $comments->keyBy('id')->each(static function (Comment $comment) {
                $comment->setRelation('replies', new Collection);
            });

            $comments->filter(static function (Comment $comment) {
                return $comment->parent_id !== null;
            })->groupBy('parent_id')->each(static function (Collection $childComments, int $parentId) use ($comments) {
                if ($comments->has($parentId)) {
                    $parent          = $comments->get($parentId);
                    $parent->replies = $parent->replies->merge($childComments);
                }
            });

            return $comments->filter(static function (Comment $comment) {
                return $comment->parent_id === null;
            })->each(static function (Comment $comment) use ($replyCountClosure) {
                $comment->reply_count = $comment->replies->count() + $comment->replies->reduce($replyCountClosure);
            });
        }

        return $comments;
    }

    /**
     * @param \Ollieread\Articles\Models\Article $article
     *
     * @return $this
     */
    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @param bool $hierarchy
     *
     * @return $this
     */
    public function setHierarchy(bool $hierarchy): self
    {
        $this->hierarchy = $hierarchy;

        return $this;
    }
}
