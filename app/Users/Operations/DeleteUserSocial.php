<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;

class DeleteUserSocial
{
    /**
     * @var string
     */
    private $provider;

    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    public function perform(): bool
    {
        return $this->user->social()
                ->where('provider', '=', $this->provider)
                ->delete() > 0;
    }

    /**
     * @param string $provider
     *
     * @return $this
     */
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

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
