<?php

namespace Ollieread\Core\Support\Rules;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Validation\Rule;
use Ollieread\Users\Models\User;

class UsersPassword implements Rule
{
    /**
     * @var \Ollieread\Users\Models\User|null
     */
    private $user;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    private $hasher;

    public function __construct(?User $user, Hasher $hasher)
    {
        $this->user   = $user;
        $this->hasher = $hasher;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return trans('validation.user_password');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if ($this->user) {
            return $this->user->password === null || $this->hasher->check($value, $this->user->password);
        }

        return false;
    }
}
