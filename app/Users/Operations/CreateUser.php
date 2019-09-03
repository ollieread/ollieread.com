<?php

namespace Ollieread\Users\Operations;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Ollieread\Users\Models\User;
use Ollieread\Users\Validators\CreateUserValidator;

class CreateUser
{
    /**
     * @var array
     */
    private $input;

    /**
     * @var string
     */
    private $provider;

    /**
     * @var \Laravel\Socialite\Contracts\User|\Laravel\Socialite\One\User|\Laravel\Socialite\Two\User
     */
    private $socialUser;

    public function perform(): ?User
    {
        if ($this->provider && $this->socialUser) {
            $this->input['avatar'] = $this->socialUser->getAvatar();
        }

        CreateUserValidator::validate($this->input);

        $user          = (new User)->fill(Arr::only($this->input, ['username', 'email', 'password', 'avatar', 'active', 'verified']));
        $user->api_key = Str::random(32);

        $role = (new GetRole)
            ->setIdent('user')
            ->perform();

        if ($role && $user->save()) {
            $user->roles()->save($role);

            if ($this->provider && $this->socialUser) {
                (new SaveUserSocial)
                    ->setProvider($this->provider)
                    ->setSocialUser($this->socialUser)
                    ->setUser($user)
                    ->perform();
            }

            return $user;
        }

        return null;
    }

    /**
     * @param array $input
     *
     * @return $this
     */
    public function setInput(array $input): self
    {
        $this->input = $input;
        return $this;
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
     * @param \Laravel\Socialite\Contracts\User|\Laravel\Socialite\One\User|\Laravel\Socialite\Two\User $socialUser
     *
     * @return $this
     */
    public function setSocialUser($socialUser): self
    {
        $this->socialUser = $socialUser;
        return $this;
    }
}
