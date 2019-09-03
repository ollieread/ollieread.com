<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;

class GetUser
{
    /**
     * @var string|null
     */
    private $provider;

    /**
     * @var string|null
     */
    private $socialId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $username;

    /**
     * @var bool
     */
    private $verifiedOnly = false;

    /**
     * @var bool
     */
    private $activeOnly = false;

    /**
     * @var int|null
     */
    private $id;

    public function perform(): ?User
    {
        $query = User::query();

        if ($this->provider && $this->socialId) {
            /**
             * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany $query
             */
            $socialClause = function ($query) {
                $query->where('provider', '=', $this->provider)
                      ->where('social_id', '=', $this->socialId);
            };

            $query->with(['social' => $socialClause])->whereHas('social', $socialClause);
        }

        if ($this->email) {
            $query->where('email', '=', $this->email);
        }

        if ($this->username) {
            $query->where('username', '=', $this->username);
        }

        if ($this->verifiedOnly) {
            $query->where('verified', '=', 1);
        }

        if ($this->activeOnly) {
            $query->where('active', '=', 1);
        }

        if ($this->id) {
            $query->where('id', '=', $this->id);
        }

        return $query->first();
    }

    /**
     * @param bool $activeOnly
     *
     * @return $this
     */
    public function setActiveOnly(bool $activeOnly): self
    {
        $this->activeOnly = $activeOnly;
        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
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
     * @param string $socialId
     *
     * @return $this
     */
    public function setSocialId(string $socialId): self
    {
        $this->socialId = $socialId;
        return $this;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param bool $verifiedOnly
     *
     * @return $this
     */
    public function setVerifiedOnly(bool $verifiedOnly): self
    {
        $this->verifiedOnly = $verifiedOnly;
        return $this;
    }
}
