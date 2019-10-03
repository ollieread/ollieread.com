<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\Role;

class GetRole
{
    /**
     * @var string
     */
    private $ident;

    /**
     * @param string $ident
     *
     * @return $this
     */
    public function setIdent(string $ident): self
    {
        $this->ident = $ident;

        return $this;
    }

    public function perform(): ?Role
    {
        $query = Role::query();

        if ($this->ident) {
            $query->where('ident', '=', $this->ident);
        }

        return $query->first();
    }
}
