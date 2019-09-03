<?php

namespace Ollieread\Users\Operations;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Laravel\Socialite\One\User as UserOne;
use Ollieread\Users\Models\Social;
use Ollieread\Users\Models\User;

class SaveUserSocial
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * @var \Ollieread\Users\Models\Social|null
     */
    private $social;

    /**
     * @var string
     */
    private $provider;

    /**
     * @var \Laravel\Socialite\Contracts\User|\Laravel\Socialite\One\User|\Laravel\Socialite\Two\User
     */
    private $socialUser;

    private function getMetadata(): array
    {
        $metadata = Arr::except($this->socialUser->getRaw(), ['email']);

        if ($this->provider === 'google') {
            $metadata['username'] = $this->socialUser->getName();
        } else {
            $metadata['username'] = $this->socialUser->getNickname();
        }

        return $metadata;
    }

    public function perform(): ?Social
    {
        $social = ($this->social ?? new Social)->fill([
            'provider'  => $this->provider,
            'social_id' => $this->socialUser->getId(),
            'metadata'  => $this->getMetadata(),
            'avatar'    => $this->socialUser->getAvatar(),
        ]);

        if ($this->socialUser instanceof UserOne) {
            $this->populateForUserOne($social);
        } else {
            $this->populateForUserTwo($social);
        }

        if (! $this->user->avatar || $this->user->avatar === $this->socialUser->getAvatar()) {
            $social->use_avatar = true;
            $this->user->avatar = $this->socialUser->getAvatar();
            $this->user->save();
        }

        if ($this->user->social()->save($social)) {
            return $social;
        }

        return null;
    }

    private function populateForUserOne(Social $social): void
    {
        $social->token  = $this->socialUser->token;
        $social->secret = $this->socialUser->tokenSecret;
    }

    private function populateForUserTwo(Social $social): void
    {
        $social->token = $this->socialUser->token;

        if ($this->socialUser->refreshToken) {
            $social->refresh_token = $this->socialUser->refreshToken;
        }

        if ($this->socialUser->expiresIn) {
            $social->expires_at = Carbon::now()->addSeconds($this->socialUser->expiresIn);
        }
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
     * @param \Ollieread\Users\Models\Social|null $social
     *
     * @return $this
     */
    public function setSocial(?Social $social): self
    {
        $this->social = $social;
        return $this;
    }

    /**
     * @param \Laravel\Socialite\Contracts\User|\Laravel\Socialite\One\User|\Laravel\Socialite\Two\User $socialUser
     *
     * @return $this
     */
    public function setSocialUser($socialUser): self
    {
        $this->socialUser = $socialUser;
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
