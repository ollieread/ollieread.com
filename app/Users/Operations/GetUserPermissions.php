<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;

class GetUserPermissions
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    public function perform()
    {
        return $this->user
            ->roles()
            ->selectRaw('BIT_OR(permissions) permissions')
            ->groupBy('user_roles.user_id')
            ->pluck('permissions')
            ->first();
    }

    /**
     * @param \Ollieread\Users\Models\User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
