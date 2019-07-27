<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Database\Eloquent\Builder;
use Ollieread\Articles\Models\Comment;

class GetArticleComment
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var \Ollieread\Articles\Models\Article|null
     */
    private $article;

    public function perform()
    {
        $query = Comment::query();

        if ($this->article) {
            $query->whereHas('article', function (Builder $query) {
                $query->where('articles.id', '=', $this->article->id);
            });
        }

        if ($this->id) {
            $query->where('id', '=', $this->id);
        }

        return $query->first();
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}