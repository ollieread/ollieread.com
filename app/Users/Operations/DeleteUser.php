<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;
use Ollieread\Users\Validators\DeleteUserValidator;

class DeleteUser
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * @var array
     */
    private $input = [];

    /**
     * @var bool
     */
    private $cascade = false;

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

    /**
     * @param bool $cascade
     *
     * @return $this
     */
    public function setCascade(bool $cascade): self
    {
        $this->cascade = $cascade;

        return $this;
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

    public function perform(): bool
    {
        DeleteUserValidator::validate($this->input);

        if ($this->cascade) {
            $this->user->social()->delete();
            $this->user->comments()->delete();
        }

        $this->user->roles()->detach();

        if ($this->user->delete()) {
            return true;
        }

        return false;
    }
}
