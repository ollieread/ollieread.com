<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Database\Eloquent\Collection;
use Ollieread\Core\Models\Topic;

class GetTopics
{
    /**
     * @var array
     */
    private $slugs;

    public function perform(): Collection
    {
        $query = Topic::query()->whereIn('slug', $this->slugs);

        return $query->get();
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