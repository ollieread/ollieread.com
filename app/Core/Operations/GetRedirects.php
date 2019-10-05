<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;

class GetRedirects
{
    /**
     * @var array
     */
    private $ids;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var bool
     */
    private $paginate = false;

    /**
     * @param array $ids
     *
     * @return $this
     */
    public function setIds(array $ids): self
    {
        $this->ids = $ids;

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function perform()
    {
        $query = Redirect::query();

        if ($this->ids) {
            $query->whereIn('id', $this->ids);
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
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit(int $limit): self
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
}
