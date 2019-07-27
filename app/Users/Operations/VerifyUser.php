<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;

class VerifyUser
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    public function perform(): bool
    {
        $this->user->verified = true;
        return $this->user->save();
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