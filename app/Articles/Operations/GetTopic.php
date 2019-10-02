<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Core\Models\Topic;

class GetTopic
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var int
     */
    private $id;

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

    public function perform(): ?Topic
    {
        $query = Topic::query();

        if ($this->slug) {
            $query->where('slug', '=', $this->slug);
        }

        if ($this->id) {
            $query->where('id', '=', $this->id);
        }

        return $query->first();
    }
}
