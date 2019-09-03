<?php

namespace Ollieread\Core\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Ollieread\Core\Support\Rules\UsersPassword;

class Rules
{
    /**
     * @return mixed|\Ollieread\Core\Support\Rules\UsersPassword
     */
    public static function password()
    {
        try {
            return Container::getInstance()->make(UsersPassword::class);
        } catch (BindingResolutionException $e) {
        }

        return null;
    }
}
