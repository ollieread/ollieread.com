<?php

namespace Ollieread\Articles\Operations;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Ollieread\Articles\Models\Category;
use Ollieread\Articles\Models\Series;
use Ollieread\Core\Support\Status;

class GetSeries
{
    /**
     * @var bool
     */
    private $activeOnly = true;

    /**
     * @var bool
     */
    private $includePrivate = false;

    /**
     * @var bool
     */
    private $includeDraft = false;

    /**
     * @var bool
     */
    private $includeReviewing = false;

    /**
     * @var null|int
     */
    private $limit;

    /**
     * @var \Ollieread\Articles\Models\Category|null
     */
    private $category;

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

    public function perform()
    {
        $query = Series::query()
            ->orderBy('post_at', 'desc')
            ->where('post_at', '<', Carbon::now());

        if (! $this->activeOnly) {
            $query->where('active', '=', 1);
        }

        $statuses = [];

        if (! $this->includePrivate) {
            $statuses[] = Status::PUBLIC;
        }

        if ($this->includeDraft) {
            $statuses[] = Status::DRAFT;
        }

        if ($this->includeReviewing) {
            $statuses[] = Status::REVIEWING;
        }

        if ($statuses) {
            if (count($statuses) === 1) {
                $query->where('status', '=', $statuses[0]);
            } else {
                $query->where(static function (Builder $query) use ($statuses) {
                    foreach ($statuses as $status) {
                        $query->orWhere('status', '=', $status);
                    }
                });
            }
        }

        if ($this->limit) {
            $query->limit($this->limit);
        }

        if ($this->category) {
            $query->whereHas('category', function (Builder $query) {
                $query->where('categories.id', '=', $this->category->id);
            });
        }

        if ($this->tags && $this->tags->isNotEmpty()) {
            $query->whereHas('tags', static function (Builder $query) {
                if ($this->tags->count() === 1) {
                    $query->where('tag.id', '=', $this->tags->first()->id);
                } else {
                    $query->whereIn('tag.id', $this->tags->pluck('id'));
                }
            });
        }

        if ($this->topics && $this->topics->isNotEmpty()) {
            $query->whereHas('topics', static function (Builder $query) {
                if ($this->topics->count() === 1) {
                    $query->where('topic.id', '=', $this->topics->first()->id);
                } else {
                    $query->whereIn('topic.id', $this->topics->pluck('id'));
                }
            });
        }

        if ($this->versions && $this->versions->isNotEmpty()) {
            $query->whereHas('versions', static function (Builder $query) {
                if ($this->versions->count() === 1) {
                    $query->where('version.id', '=', $this->versions->first()->id);
                } else {
                    $query->whereIn('version.id', $this->versions->pluck('id'));
                }
            });
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
     * @param bool $includeDraft
     *
     * @return $this
     */
    public function setIncludeDraft(bool $includeDraft): self
    {
        $this->includeDraft = $includeDraft;

        return $this;
    }

    /**
     * @param bool $includePrivate
     *
     * @return $this
     */
    public function setIncludePrivate(bool $includePrivate): self
    {
        $this->includePrivate = $includePrivate;

        return $this;
    }

    /**
     * @param bool $includeReviewing
     *
     * @return $this
     */
    public function setIncludeReviewing(bool $includeReviewing): self
    {
        $this->includeReviewing = $includeReviewing;

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
}
