<?php

namespace Ollieread\Users\Operations;

use Ollieread\Users\Models\User;

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

    public function perform(): void
    {

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