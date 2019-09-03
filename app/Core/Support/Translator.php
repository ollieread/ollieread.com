<?php

namespace Ollieread\Core\Support;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator as BaseTranslator;
use Psr\Log\LoggerInterface;

class Translator extends BaseTranslator
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(Loader $loader, string $locale, LoggerInterface $logger)
    {
        parent::__construct($loader, $locale);
        $this->logger = $logger;
    }

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $translation = parent::get($key, $replace, $locale, $fallback);
        // The "translation" is unchanged from the key.
        if ($translation === $key && substr_count($key, ' ') === 0 && substr_count($key, 'validation.') === 0) {
            $this->logMissingTranslation($key, $replace, $locale, $fallback);
        }

        return $translation;
    }

    private function logMissingTranslation(string $key, array $replace, $locale, bool $fallback): void
    {
        $this->logger->notice('Missing translation: ' . $key, [
            'replacements' => $replace,
            'locale'       => $locale ?: config('app.locale'),
            'fallback'     => $fallback ? config('app.fallback_locale') : '',
        ]);
    }
}