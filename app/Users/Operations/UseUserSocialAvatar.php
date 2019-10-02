<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\Social;
use Ollieread\Users\Models\User;

class UseUserSocialAvatar
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * @var string
     */
    private $provider;

    public function perform(): bool
    {
        /**
         * @var Social|null $newAvatarSocial
         */
        $newAvatarSocial = null;

        (new GetUserSocials)->setUser($this->user)->perform()->each(function (Social $social) use (&$newAvatarSocial) {
            if ($social->provider === $this->provider) {
                $social->use_avatar = true;
                $newAvatarSocial    = $social;
            } else {
                $social->use_avatar = false;
            }

            if ($social->isDirty()) {
                $social->save();
            }
        });

        if ($newAvatarSocial) {
            $this->user->setAttribute('avatar', $newAvatarSocial->avatar);

            return $this->user->save();
        }

        return false;
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
