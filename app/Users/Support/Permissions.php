<?php

namespace Ollieread\Users\Support;

class Permissions
{
    public const ADMIN_ARTICLES = 512;

    public const ADMIN_COURSES  = 2048;

    public const ADMIN_MASTER   = 8192;

    public const ADMIN_PACKAGES = 1024;

    public const ADMIN_SERVICES = 4096;

    public const ADMIN_USERS    = 256;

    public const ALL            = [
        'CREATE_COMMENT' => self::CREATE_COMMENT,
        'UNUSED_1'       => self::UNUSED_1,
        'UNUSED_2'       => self::UNUSED_2,
        'UNUSED_3'       => self::UNUSED_3,
        'UNUSED_4'       => self::UNUSED_4,
        'UNUSED_5'       => self::UNUSED_5,
        'UNUSED_6'       => self::UNUSED_6,
        'UNUSED_7'       => self::UNUSED_7,
        'ADMIN_USERS'    => self::ADMIN_USERS,
        'ADMIN_ARTICLES' => self::ADMIN_ARTICLES,
        'ADMIN_PACKAGES' => self::ADMIN_PACKAGES,
        'ADMIN_COURSES'  => self::ADMIN_COURSES,
        'ADMIN_SERVICES' => self::ADMIN_SERVICES,
        'ADMIN_MASTER'   => self::ADMIN_MASTER,
    ];

    public const CREATE_COMMENT = 1;

    public const UNUSED_1       = 2;

    public const UNUSED_2       = 4;

    public const UNUSED_3       = 8;

    public const UNUSED_4       = 16;

    public const UNUSED_5       = 32;

    public const UNUSED_6       = 64;

    public const UNUSED_7       = 128;

    public static function get(string $key): int
    {
        return self::ALL[$key] ?? 0;
    }

    public static function toInt(string ...$permissions): int
    {
        return array_reduce($permissions, static function ($carry, $item) {
            return (int) $carry | self::get($item);
        });
    }

    public static function toArray(int $permissions): array
    {
        $parsedPermissions = [];

        foreach (self::ALL as $key => $value) {
            $parsedPermissions[$key] = (bool) ($permissions & $value);
        }

        return $parsedPermissions;
    }
}
