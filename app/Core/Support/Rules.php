<?php

namespace Ollieread\Core\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Ollieread\Core\Support\Rules\ArticleLive;
use Ollieread\Core\Support\Rules\UsersPassword;

class Rules
{
    /**
     * @return mixed|\Ollieread\Core\Support\Rules\UsersPassword
     */
    public static function password(): ?UsersPassword
    {
        try {
            return Container::getInstance()->make(UsersPassword::class);
        } catch (BindingResolutionException $e) {
        }

        return null;
    }

    /**
     * @param array $data
     *
     * @return \Ollieread\Core\Support\Rules\ArticleLive
     */
    public static function live(array $data): ArticleLive
    {
        return new ArticleLive($data);
    }
}
