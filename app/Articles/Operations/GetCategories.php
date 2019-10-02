<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Models\Category;

class GetCategories
{
    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var array
     */
    private $slugs;

    public function perform()
    {
        $query = Category::query();

        if ($this->limit) {
            $query->limit($this->limit);
        }

        if ($this->slugs) {
            $query->whereIn('slug', '=', $this->slugs);
        }

        return $query->get();
    }

    /**
     * @param int|null $limit
     *
     * @return $this
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param array $slugs
     *
     * @return $this
     */
    public function setSlugs(array $slugs): self
    {
        $this->slugs = $slugs;

        return $this;
    }
}
