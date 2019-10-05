<?php

namespace Ollieread\Core\Services;

use Illuminate\Container\Container;
use Illuminate\View\Factory;

class Codebase
{
    public static function version()
    {
        try {
            $version = null;
            $json    = json_decode(file_get_contents(base_path('composer.json')), true, 512);
            $version = 'v' . $json['version'];

            return Container::getInstance()->make(Factory::class)->make('partials.version', compact('version'));
        } catch (\Exception $exception) {
        }

        return '';
    }
}
