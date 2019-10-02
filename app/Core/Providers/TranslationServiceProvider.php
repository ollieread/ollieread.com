<?php

namespace Ollieread\Core\Providers;

use Carbon\Carbon;
use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Ollieread\Core\Support\Translator;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{
    /**
     * @throws \Exception
     */
    public function register()
    {
        $this->registerLoader();

        if ($this->app->environment() !== 'local') {
            parent::register();

            return;
        }

        $this->app->singleton('translator', static function ($app) {
            $logger = new Logger('import');
            $logger->pushHandler(new StreamHandler(storage_path('translations-' . Carbon::now()->format('Ymd') . '.log')));

            $trans = new Translator(
                $app['translation.loader'],
                $app['config']['app.locale'],
                $logger
            );

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
