<?php

namespace Ollieread\Articles\Operations;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Models\Category;
use Ollieread\Articles\Models\Series;

class GetArticles
{
    /**
     * @var bool
     */
    private $activeOnly = false;

    /**
     * @var array<int>
     */
    private $statuses = [];

    /**
     * @var null|int
     */
    private $limit;

    /**
     * @var \Ollieread\Articles\Models\Category
     */
    private $category;

    /**
     * @var \Illuminate\Support\Collection|null
     */
    private $categories;

    /**
     * @var \Illuminate\Support\Collection|null
     */
    private $tags;

    /**
     * @var \Illuminate\Support\Collection|null
     */
    private $topics;

    /**
     * @var \Illuminate\Support\Collection|null
     */
    private $versions;

    /**
     * @var \Ollieread\Articles\Models\Series|null
     */
    private $series;

    /**
     * @var bool
     */
    private $paginate = false;

    /**
     * @var bool
     */
    private $liveOnly;

    public function perform()
    {
        $query = Article::query();

        if ($this->liveOnly) {
            $query->where('post_at', '<', Carbon::now());
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

        if ($this->category) {
            $query->whereHas('category', function (Builder $query) {
                $query->where('categories.id', '=', $this->category->id);
            });
        }

        if ($this->categories && $this->categories->isNotEmpty()) {
            $query->whereHas('category', function (Builder $query) {
                if ($this->tags->count() === 1) {
                    $query->where('categories.id', '=', $this->categories->first()->id);
                } else {
                    $query->whereIn('categories.id', $this->categories->pluck('id'));
                }
            });
        }

        if ($this->tags && $this->tags->isNotEmpty()) {
            $query->whereHas('tags', function (Builder $query) {
                if ($this->tags->count() === 1) {
                    $query->where('tags.id', '=', $this->tags->first()->id);
                } else {
                    $query->whereIn('tags.id', $this->tags->pluck('id'));
                }
            });
        }

        if ($this->topics && $this->topics->isNotEmpty()) {
            $query->whereHas('topics', function (Builder $query) {
                if ($this->topics->count() === 1) {
                    $query->where('topics.id', '=', $this->topics->first()->id);
                } else {
                    $query->whereIn('topics.id', $this->topics->pluck('id'));
                }
            });
        }

        if ($this->versions && $this->versions->isNotEmpty()) {
            $query->whereHas('versions', function (Builder $query) {
                if ($this->versions->count() === 1) {
                    $query->where('versions.id', '=', $this->versions->first()->id);
                } else {
                    $query->whereIn('versions.id', $this->versions->pluck('id'));
                }
            });
        }

        if ($this->series) {
            $query->whereHas('series', function (Builder $query) {
                $query->where('series.id', '=', $this->series);
            })->orderBy('post_at');
        } else {
            $query->orderBy('post_at', 'desc');
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
     * @param \Illuminate\Support\Collection|null $categories
     *
     * @return $this
     */
    public function setCategories(?Collection $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @param \Ollieread\Articles\Models\Category|null $category
     *
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
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
     * @param \Ollieread\Articles\Models\Series|null $series
     *
     * @return $this
     */
    public function setSeries(?Series $series): self
    {
        $this->series = $series;

        return $this;
    }

    /**
     * @param \Illuminate\Support\Collection|null $tags
     *
     * @return $this
     */
    public function setTags(?Collection $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @param \Illuminate\Support\Collection|null $topics
     *
     * @return $this
     */
    public function setTopics(?Collection $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * @param \Illuminate\Support\Collection|null $versions
     *
     * @return $this
     */
    public function setVersions(?Collection $versions): self
    {
        $this->versions = $versions;

        return $this;
    }

    /**
     * @param bool $liveOnly
     *
     * @return $this
     */
    public function setLiveOnly(bool $liveOnly): self
    {
        $this->liveOnly = $liveOnly;

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
}
