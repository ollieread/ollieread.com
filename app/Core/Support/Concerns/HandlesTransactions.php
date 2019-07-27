<?php

namespace Ollieread\Core\Support\Concerns;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Database\DatabaseManager;

trait HandlesTransactions
{
    /**
     * @var null|\Illuminate\Database\DatabaseManager
     */
    private static $manager;

    /**
     * @param \Closure $transaction
     * @param int      $attempts
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    public static function transaction(Closure $transaction = null, int $attempts = 1)
    {
        if ($transaction) {
            return self::manager()->transaction($transaction, $attempts);
        }

        self::manager()->beginTransaction();
    }

    /**
     *
     */
    public static function commit(): void
    {
        self::$manager->commit();
    }

    /**
     * @param int|null $toLevel
     *
     * @throws \Exception
     */
    public static function rollback(?int $toLevel = null): void
    {
        self::$manager->rollBack($toLevel);
    }

    /**
     * @return \Illuminate\Database\DatabaseManager|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private static function manager(): DatabaseManager
    {
        if (! (self::$manager instanceof DatabaseManager)) {
            self::$manager = Container::getInstance()->make(DatabaseManager::class);
        }

        return self::$manager;
    }
}