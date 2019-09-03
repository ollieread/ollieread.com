<?php

namespace Ollieread\Users\Support;

class Permissions
{
    public const CREATE_COMMENT        = 1;
    public const MANAGE_API_KEYS       = 2;
    public const BILLING_DETAILS       = 4;
    public const BILLING_PURCHASES     = 8;
    public const BILLING_SUBSCRIPTIONS = 16;
    public const PRODUCT_LICENSES      = 32;
    public const PRODUCT_COURSES       = 64;
    public const PRODUCT_PROJECTS      = 128;
    public const ADMIN_USERS           = 256;
    public const ADMIN_ARTICLES        = 512;
    public const ADMIN_PACKAGES        = 1024;
    public const ADMIN_COURSES         = 2048;
    public const ADMIN_SERVICES        = 4096;
    public const ADMIN_MASTER          = 8192;

    public const ALL = [
        'CREATE_COMMENT'        => self::CREATE_COMMENT,
        'MANAGE_API_KEYS'       => self::MANAGE_API_KEYS,
        'BILLING_DETAILS'       => self::BILLING_DETAILS,
        'BILLING_PURCHASES'     => self::BILLING_PURCHASES,
        'BILLING_SUBSCRIPTIONS' => self::BILLING_SUBSCRIPTIONS,
        'PRODUCT_LICENSES'      => self::PRODUCT_LICENSES,
        'PRODUCT_COURSES'       => self::PRODUCT_COURSES,
        'PRODUCT_PROJECTS'      => self::PRODUCT_PROJECTS,
        'ADMIN_USERS'           => self::ADMIN_USERS,
        'ADMIN_ARTICLES'        => self::ADMIN_ARTICLES,
        'ADMIN_PACKAGES'        => self::ADMIN_PACKAGES,
        'ADMIN_COURSES'         => self::ADMIN_COURSES,
        'ADMIN_SERVICES'        => self::ADMIN_SERVICES,
        'ADMIN_MASTER'          => self::ADMIN_MASTER,
    ];

    public static function get(string $key): int
    {
        return self::ALL[$key] ?? 0;
    }

    public static function toInt(string ...$permissions): int
    {
        return array_reduce($permissions, static function ($carry, $item) {
            return (int)$carry | self::get($item);
        });
    }

    public static function toArray(int $permissions): array
    {
        $parsedPermissions = [];

        foreach (self::ALL as $key => $value) {
            $parsedPermissions[$key] = (bool)($permissions & $value);
        }

        return $parsedPermissions;
    }
}
