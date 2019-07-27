<?php

namespace Ollieread\Articles\Composers;

use Illuminate\View\View;
use Ollieread\Articles\Operations\GetSeries;

class SeriesSidebarComposer
{
    public function compose(View $view): void
    {
        $series = (new GetSeries)
            ->setLimit(4)
            ->setActiveOnly(true)
            ->perform();
        $view->with('series', $series);
    }
}