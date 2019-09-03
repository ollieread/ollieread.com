<?php

namespace Ollieread\Core\Actions;

use Illuminate\Http\Request;
use Ollieread\Core\Services\Redirects;
use Ollieread\Core\Support\Action;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Fallback extends Action
{
    public function __invoke(Request $request)
    {
        $redirect = Redirects::getRedirect($request->getRequestUri());

        if ($redirect) {
            return $this->response()->redirectTo($redirect->to, 301);
        }

        throw new NotFoundHttpException;
    }
}