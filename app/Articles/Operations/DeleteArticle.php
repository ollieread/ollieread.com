<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Validators\DeleteArticleValidator;

class DeleteArticle
{
    /**
     * @var \Ollieread\Articles\Models\Article
     */
    private $article;

    /**
     * @var array
     */
    private $input;

    public function perform(): bool
    {
        DeleteArticleValidator::validate($this->input, $this->article);

        $this->article->comments()->delete();
        $this->article->tags()->detach();
        $this->article->versions()->detach();
        $this->article->topics()->detach();

        return $this->article->delete();
    }

    /**
     * @param array $input
     *
     * @return $this
     */
    public function setInput(array $input): self
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param \Ollieread\Core\Models\Version $article
     *
     * @return $this
     */
    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
