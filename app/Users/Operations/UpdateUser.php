<?php

namespace Ollieread\Users\Operations;

use Illuminate\Support\Arr;
use Ollieread\Users\Models\User;
use Ollieread\Users\Validators\UpdateUserDetailsValidator;
use Ollieread\Users\Validators\UpdateUserValidator;

class UpdateUser
{

    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * @var array
     */
    private $input;

    /**
     * @var bool
     */
    private $admin = false;

    public function perform(): bool
    {
        if (! $this->admin) {
            UpdateUserDetailsValidator::validate($this->input, $this->user);
        } else {
            UpdateUserValidator::validate($this->input, $this->user);
        }

        $this->user->fill(Arr::except($this->input, 'roles'));

        if ($this->user->save()) {
            if (isset($this->input['roles'])) {
                $this->user->roles()->sync($this->input['roles']);
            }

            return true;
        }

        return false;
    }

    /**
     * @param bool $admin
     *
     * @return $this
     */
    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;
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
