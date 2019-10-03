<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;
use Ollieread\Users\Validators\UpdateUserPasswordValidator;

class UpdateUserPassword
{

    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * @var array
     */
    private $input;

    public function perform(): bool
    {
        UpdateUserPasswordValidator::validate($this->input, $this->user);

        $this->user->password = $this->input['password'];

        if ($this->user->save()) {
            return true;
        }

        return false;
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
