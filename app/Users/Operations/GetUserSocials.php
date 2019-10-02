<?php

namespace Ollieread\Users\Operations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Ollieread\Users\Models\Social;
use Ollieread\Users\Models\User;

class GetUserSocials
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    public function perform(): Collection
    {
        return Social::query()
            ->whereHas('user', function (Builder $query) {
                $query->where('id', '=', $this->user->getKey());
            })
            ->get();
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
