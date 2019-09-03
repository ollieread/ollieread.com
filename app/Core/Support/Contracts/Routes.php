<?php

namespace Ollieread\Core\Support\Contracts;

use Illuminate\Routing\Router;

interface Routes
{
    public function __invoke(Router $router);

    public function name(): ?string;

    public function prefix(): ?string;
}
