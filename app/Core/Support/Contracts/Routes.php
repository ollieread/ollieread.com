<?php

namespace Ollieread\Core\Support\Contracts;

use Illuminate\Routing\Router;

interface Routes
{
    public function __invoke(Router $router);

    public function prefix(): ?string;

    public function name(): ?string;
}