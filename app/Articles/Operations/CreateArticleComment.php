<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Models\Comment;
use Ollieread\Articles\Validators\CreateCommentValidator;
use Ollieread\Users\Models\User;

class CreateArticleComment
{
    /**
     * @var \Ollieread\Articles\Models\Article
     */
    private $article;

    /**
     * @var \Ollieread\Users\Models\User
     */
    private $author;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var \Ollieread\Articles\Models\Comment|null
     */
    private $parent;

    public function perform(): ?Comment
    {
        CreateCommentValidator::validate(['comment' => $this->comment]);

        $comment = (new Comment)->fill([
            'comment' => $this->comment,
            'active'  => true,
        ]);

        $comment->article()->associate($this->article);
        $comment->author()->associate($this->author);

        if ($this->parent) {
            $comment->parent()->associate($this->parent);
        }

        if ($comment->save()) {
            return $comment;
        }

        return null;
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
     * @param \Ollieread\Users\Models\User $author
     *
     * @return $this
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @param string $comment
     *
     * @return $this
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @param \Ollieread\Articles\Models\Comment|null $parent
     *
     * @return $this
     */
    public function setParent(?Comment $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
