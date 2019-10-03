<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Models\Category;

class GetCategory
{
    /**
     * @var string|null
     */
    private $slug;

    /**
     * @var int|null
     */
    private $id;

    public function perform(): ?Category
    {
        $query = Category::query();

        if ($this->slug) {
            $query->where('slug', '=', $this->slug);
        } else if ($this->id) {
            $query->where('id', '=', $this->id);
        }

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
