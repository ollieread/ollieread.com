<?php

namespace Ollieread\Kernels\Core;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Console extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        if (! $this->app->environment('production')) {
            $this->command('translation:missing', function () {
                $files        = Finder::create()
                                      ->files()
                                      ->name('translations-*.log')
                                      ->in(storage_path());
                $translations = [];

                foreach ($files as $file) {
                    $contents = $file->getContents();
                    $lines    = explode("\n", $contents);

                    foreach ($lines as $line) {
                        if (preg_match("/\[(.*)\] import\.NOTICE\: Missing translation\: ([a-z\.\/]*) (.*) \[\]/", $line, $matches) !== false) {
                            if (isset($matches[2]) && ! in_array($matches[2], $translations, true)) {
                                if (trans($matches[2]) !== $matches[2]) {
                                    $translations[] = $matches[2];
                                }
                            }
                        }
                    }
                }

                sort($translations);
                file_put_contents(storage_path('translations.txt'), implode("\n", $translations));
                (new Filesystem)->remove($files);
            });
        }
    }
}
