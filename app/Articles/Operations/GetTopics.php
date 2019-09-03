<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Core\Models\Topic;

class GetTopics
{
    /**
     * @var array
     */
    private $slugs;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var bool
     */
    private $paginate = false;

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function perform()
    {
        $query = Topic::query();

        if ($this->slugs) {
            $query->whereIn('slug', $this->slugs);
        }

        if ($this->paginate) {
            return $query->paginate($this->limit ?? 20);
        }

        if ($this->limit) {
            $query->limit($this->limit);
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
     * @param bool $paginate
     *
     * @return $this
     */
    public function setPaginate(bool $paginate): self
    {
        $this->paginate = $paginate;
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
