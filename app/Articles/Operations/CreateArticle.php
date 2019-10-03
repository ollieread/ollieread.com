<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Support\Arr;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Validators\CreateArticleValidator;

class CreateArticle
{
    /**
     * @var array
     */
    private $input;

    public function perform(): bool
    {
        CreateArticleValidator::validate($this->input);

        $article = (new Article)->fill(Arr::only($this->input, [
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
                $article->category()->associate($category);
            }
        }

        if ($this->input['topics']) {
            $topics = (new GetTopics)->setIds($this->input['topics'])->perform();

            if ($topics) {
                $article->topics()->sync($topics->pluck('id'));
            }
        }

        if ($this->input['versions']) {
            $versions = (new GetVersions)->setIds($this->input['versions'])->perform();

            if ($versions) {
                $article->versions()->sync($versions->pluck('id'));
            }
        }

        if ($this->input['image']) {
            $image = (new CreateArticleImage)->setUpload($this->input['image'])->perform();

            if ($image) {
                $article->setAttribute('image', $image);
            }
        }

        return $article->save();
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
