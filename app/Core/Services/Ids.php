<?php

namespace Ollieread\Core\Services;

use Hashids\Hashids;

/**
 * Class Ids
 *
 * @method static string encodeCategories(int $id)
 * @method static string encodePosts(int $id)
 * @method static string encodeProjects(int $id)
 * @method static string encodeTags(int $id)
 * @method static string encodeSeries(int $id)
 * @method static int decodeCategories(string $id)
 * @method static int decodePosts(string $id)
 * @method static int decodeProjects(string $id)
 * @method static int decodeTags(string $id)
 * @method static int decodeSeries(string $id)
 *
 * @package Ollieread\Services
 */
class Ids
{
    /**
     * @var \Hashids\Hashids|null
     */
    protected static $categories;

    /**
     * @var \Hashids\Hashids|null
     */
    protected static $posts;

    /**
     * @var \Hashids\Hashids|null
     */
    protected static $projects;

    /**
     * @var \Hashids\Hashids|null
     */
    protected static $tags;

    /**
     * @var \Hashids\Hashids|null
     */
    protected static $series;

    /**
     * @return \Hashids\Hashids
     */
    public static function categories(): Hashids
    {
        if (! (self::$categories instanceof Hashids)) {
            self::$categories = new Hashids(config('services.hashids.categories'), 6);
        }

        return self::$categories;
    }

    /**
     * @return \Hashids\Hashids
     */
    public static function posts(): Hashids
    {
        if (! (self::$posts instanceof Hashids)) {
            self::$posts = new Hashids(config('services.hashids.posts'), 6);
        }

        return self::$posts;
    }

    /**
     * @return \Hashids\Hashids
     */
    public static function projects(): Hashids
    {
        if (! (self::$projects instanceof Hashids)) {
            self::$projects = new Hashids(config('services.hashids.projects'), 6);
        }

        return self::$projects;
    }

    /**
     * @return \Hashids\Hashids
     */
    public static function tags(): Hashids
    {
        if (! (self::$tags instanceof Hashids)) {
            self::$tags = new Hashids(config('services.hashids.tags'), 6);
        }

        return self::$tags;
    }

    /**
     * @return \Hashids\Hashids
     */
    public static function series(): Hashids
    {
        if (! (self::$seriesx instanceof Hashids)) {
            self::$series = new Hashids(config('services.hashids.series'), 6);
        }

        return self::$series;
    }

    public static function __callStatic($name, $arguments)
    {
        if (substr_count($name, 'encode')) {
            return self::encode(strtolower(str_replace('encode', '', $name)), ...$arguments);
        }

        if (substr_count($name, 'decode')) {
            return self::decode(strtolower(str_replace('decode', '', $name)), ...$arguments);
        }
    }

    public static function encode(string $type, int $id)
    {
        if (method_exists(self::class, $type)) {
            return self::{$type}()->encode($id);
        }

        return null;
    }

    public static function decode(string $type, int $id)
    {
        if (method_exists(self::class, $type)) {
            $ids = self::{$type}()->decode($id);

            return array_shift($ids);
        }

        return null;
    }
}
