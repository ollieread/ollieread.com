<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\Role;

class GetRoles
{
    /**
     * @var int|null
     */
    private $limit;

    public function perform()
    {
        $query = Role::query();

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
