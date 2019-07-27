<?php

namespace Ollieread\Core\Middleware;

use Closure;

class HandleRedirectTo
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->query->has('redirect_to')) {
            $request->session()->put('redirect_to', $request->query('redirect_to'));
        }

        return $next($request);
    }
}
