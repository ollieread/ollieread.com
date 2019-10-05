<?php

namespace Ollieread\Admin\Actions\Redirects;

use Ollieread\Core\Operations\GetRedirects;
use Ollieread\Core\Support\Action;

class Index extends Action
{
    public function __invoke()
    {
        $redirects = (new GetRedirects)
            ->setLimit(20)
            ->setPaginate(true)
            ->perform();

        return $this->response()->view('admin.redirects.index', compact('redirects'));
    }
}
