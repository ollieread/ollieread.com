<?php

namespace Ollieread\Core\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Ollieread\Core\Operations\GetRedirect;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(route('users:sign-in.create'));
    }

    public function render($request, Exception $e)
    {
        if ($e instanceof NotFoundHttpException) {
            $redirect = (new GetRedirect)->setUri($request->getRequestUri())->perform();

            if ($redirect) {
                return response()->redirectTo($redirect->to);
            }
        }

        return parent::render($request, $e);
    }
}
