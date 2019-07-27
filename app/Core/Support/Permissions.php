<?php

namespace Ollieread\Core\Support;

final class Permissions
{
    public const ADMIN = 0x00000001;

    private static $permissions = [

    ];

    /**
     * @param $permission
     *
     * @return bool
     */
    public static function exists($permission): bool
    {
        return in_array($permission, self::$permissions, true);
    }

    /**
     * @param $permission
     *
     * @return int
     */
    public static function getInt($permission): int
    {
        $key = array_search($permission, self::$permissions, true);

        if ($key) {
            return $key ** 2;
        }

        return 0;
    }

    /**
     * @param int $permissionInt
     * @param     $permission
     *
     * @return int
     */
    public static function has(int $permissionInt, $permission): int
    {
        $int = self::getInt($permission);

        if ($int > 0) {
            return $permissionInt & $int;
        }

        return false;
    }

    /**
     * @param int $permissions
     *
     * @return array
     */
    public static function toArray(int $permissions): array
    {
        $parsedPermissions = [];

        foreach (self::$permissions as $permission => $value) {
            $parsedPermissions[$value] = (bool)($permissions & ($permission ** 2));
        }

        return $parsedPermissions;
    }

    /**
     * @param array $permissions
     *
     * @return int
     */
    public static function toInt(array $permissions): int
    {
        $parsedPermissions = 0;

        foreach ($permissions as $permission => $presence) {
            if ($presence) {
                $parsedPermissions |= self::getInt($permission);
            }
        }

        return $parsedPermissions;
    }
}