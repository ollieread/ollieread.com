<?php

namespace Ollieread\Core\Services;

use Ollieread\Core\Models\Redirect;

class Redirects
{
    public static function create(string $from, string $to): void
    {
        if (self::alreadyPresent($from, $to)) {
            self::updateExisting($from, $to);
            self::createNew($from, $to);
        }
    }

    public static function alreadyPresent(string $from, string $to): bool
    {
        return Redirect::query()
                ->where('from', '=', $from)
                ->count() === 0;
    }

    public static function updateExisting(string $from, string $to): void
    {
        Redirect::query()->where('to', '=', $from)->update(['to' => $to]);
    }

    public static function createNew(string $from, string $to)
    {
        (new Redirect)->fill(compact('from', 'to'))->save();
    }

    public static function getRedirect(string $uri)
    {
        return Redirect::query()->where('from', '=', $uri)->first();
    }
}
