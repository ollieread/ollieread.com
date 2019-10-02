<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;

class GetUsers
{
    /**
     * @var int|null
     */
    private $limit;

    public function perform()
    {
        $query = User::query();

        if ($this->limit) {
            return $query->paginate($this->limit);
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
}
