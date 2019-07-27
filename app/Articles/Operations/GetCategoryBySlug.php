<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Models\Category;

class GetCategoryBySlug
{
    /**
     * @var string
     */
    private $slug;

    public function perform(): ?Category
    {
        $query = Category::query()->where('slug', '=', $this->slug);

        return $query->first();
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }
}