<?php

namespace Ollieread\Core\Support;

use Illuminate\Container\Container;
use Ollieread\Core\Support\Rules\UsersPassword;

class Rules
{
    /**
     * @return mixed|\Ollieread\Core\Support\Rules\UsersPassword
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function password()
    {
        return Container::getInstance()->make(UsersPassword::class);
    }
}