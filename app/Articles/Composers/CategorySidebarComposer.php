<?php

namespace Ollieread\Articles\Composers;

use Illuminate\View\View;
use Ollieread\Articles\Operations\GetCategories;

class CategorySidebarComposer
{
    public function compose(View $view): void
    {
        $categories = (new GetCategories)->perform();
        $view->with('categories', $categories);
    }
}