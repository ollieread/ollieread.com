<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Support\Arr;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Validators\UpdateArticleValidator;

class UpdateArticle
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
        UpdateArticleValidator::validate($this->input, $this->article);

        $this->article->fill(Arr::only($this->input, [
            'name',
            'title',
            'heading',
            'seo_description',
            'slug',
            'excerpt',
            'content',
            'active',
            'status',
            'post_at',
        ]));

        if ($this->input['category']) {
            $category = (new GetCategory())->setId($this->input['category'])->perform();

            if ($category) {
                $this->article->category()->associate($category);
            }
        }

        if ($this->input['topics']) {
            $topics = (new GetTopics)->setIds($this->input['topics'])->perform();

            if ($topics) {
                $this->article->topics()->sync($topics->pluck('id'));
            }
        }

        if ($this->input['versions']) {
            $versions = (new GetVersions)->setIds($this->input['versions'])->perform();

            if ($versions) {
                $this->article->versions()->sync($versions->pluck('id'));
            }
        }

        return $this->article->save();
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
     * @param \Ollieread\Articles\Models\Article $article
     *
     * @return $this
     */
    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
