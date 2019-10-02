<?php

namespace Ollieread\Core\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Session\Store;

/**
 * Class Alerts
 *
 * @package Ollieread\Support
 */
class Alerts
{
    /**
     *
     */
    public const ERROR = 'error';

    /**
     *
     */
    public const INFO = 'info';

    /**
     *
     */
    public const SUCCESS = 'success';

    /**
     *
     */
    public const WARNING = 'warning';

    /**
     * @param string|null $type
     * @param string      $context
     *
     * @return mixed
     */
    public static function messages(string $type = null, string $context = 'default')
    {
        if ($type) {
            return self::session()->pull(sprintf('alerts.%s.%s', $context, $type), []);
        }

        return self::session()->pull(sprintf('alerts.%s', $context), []);
    }

    /**
     * @param string $type
     * @param string $message
     * @param string $context
     */
    public static function message(string $type, string $message, string $context = 'default'): void
    {
        self::session()->push(sprintf('alerts.%s.%s', $context, $type), $message);
    }

    /**
     * @param string $message
     * @param string $context
     */
    public static function success(string $message, string $context = 'default'): void
    {
        self::message(self::SUCCESS, $message, $context);
    }

    /**
     * @param string $message
     * @param string $context
     */
    public static function error(string $message, string $context = 'default'): void
    {
        self::message(self::ERROR, $message, $context);
    }

    /**
     * @param string $message
     * @param string $context
     */
    public static function warning(string $message, string $context = 'default'): void
    {
        self::message(self::WARNING, $message, $context);
    }

    /**
     * @param string $message
     * @param string $context
     */
    public static function info(string $message, string $context = 'default'): void
    {
        self::message(self::INFO, $message, $context);
    }

    /**
     * @return \Illuminate\Session\Store
     */
    private static function session(): Store
    {
        try {
            return Container::getInstance()->make(Store::class);
        } catch (BindingResolutionException $exception) {
            report($exception);
        }
    }
}
