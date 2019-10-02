<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Database\Eloquent\Builder;
use Ollieread\Articles\Models\Article;

class GetArticle
{
    /**
     * @var string|null
     */
    private $slug;

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var bool
     */
    private $activeOnly = false;

    /**
     * @var array<int>
     */
    private $statuses = [];

    public function perform(): ?Article
    {
        $query = Article::query();

        if ($this->slug) {
            $query->where('slug', '=', $this->slug);
        }

        if ($this->id) {
            $query->where('id', '=', $this->id);
        }

        if ($this->activeOnly) {
            $query->where('active', '=', 1);
        }

        if ($this->statuses) {
            if (count($this->statuses) === 1) {
                $query->where('status', '=', $this->statuses[0]);
            } else {
                $query->where(static function (Builder $query) {
                    foreach ($this->statuses as $status) {
                        $query->orWhere('status', '=', $status);
                    }
                });
            }
        }

        return $query->first();
    }

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param bool $activeOnly
     *
     * @return $this
     */
    public function setActiveOnly(bool $activeOnly): self
    {
        $this->activeOnly = $activeOnly;

        return $this;
    }

    /**
     * @param array<int> $statuses
     *
     * @return $this
     */
    public function setStatuses(int ...$statuses): self
    {
        $this->statuses = $statuses;

        return $this;
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
