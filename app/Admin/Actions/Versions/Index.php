<?php

namespace Ollieread\Admin\Actions\Versions;

use Ollieread\Articles\Operations\GetVersions;
use Ollieread\Core\Support\Action;

class Index extends Action
{
    public function __invoke()
    {
        $versions = (new GetVersions)
            ->setLimit(20)
            ->setPaginate(true)
            ->perform();

        return $this->response()->view('admin.versions.index', compact('versions'));
    }
}
