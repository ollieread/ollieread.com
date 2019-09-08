<?php

namespace Ollieread\Core\Middleware;

use Closure;
use Ollieread\Core\Services\Honeypot;

class CheckHoneypot
{
    /**
     * @var \Ollieread\Core\Services\Honeypot
     */
    private $honeypot;

    public function __construct(Honeypot $honeypot)
    {
        $this->honeypot = $honeypot;
    }

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
        if ($this->honeypot->validate($request)) {
            return $next($request);
        }

        return response()->redirectTo('/');
    }
}
