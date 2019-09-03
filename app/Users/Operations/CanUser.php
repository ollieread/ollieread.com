<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;
use Ollieread\Users\Support\Permissions;

class CanUser
{
    /**
     * @var int[]
     */
    private $permissions = [];

    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    public function perform()
    {
        return $this->user
            ->roles()
            ->selectRaw('IF(BIT_AND(permissions) & ' . Permissions::toInt(...$this->permissions) . ' > 0, 1, 0) has_permission')
            ->groupBy('user_roles.user_id')
            ->first('has_permission');
    }

    /**
     * @param int[] $permissions
     *
     * @return $this
     */
    public function setPermissions(int ...$permissions): self
    {
        $this->permissions = $permissions;
        return $this;
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
