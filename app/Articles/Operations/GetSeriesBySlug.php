<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Database\Eloquent\Builder;
use Ollieread\Articles\Models\Series;
use Ollieread\Core\Support\Status;

class GetSeriesBySlug
{
    /**
     * @var string
     */
    private $slug;

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

    public function perform(): ?Series
    {
        $query = Series::query()->where('slug', '=', $this->slug);

        if ($this->activeOnly) {
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

        return $query->first();
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
