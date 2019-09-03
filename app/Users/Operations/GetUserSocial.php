<?php

namespace Ollieread\Users\Operations;

use Illuminate\Database\Eloquent\Builder;
use Ollieread\Users\Models\Social;
use Ollieread\Users\Models\User;

class GetUserSocial
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * @var string
     */
    private $provider;

    public function perform(): ?Social
    {
        return Social::query()
                     ->whereHas('user', function (Builder $query) {
                         $query->where('id', '=', $this->user->getKey());
                     })
                     ->where('provider', '=', $this->provider)
                     ->first();
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
