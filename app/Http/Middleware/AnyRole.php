<?php

namespace App\Http\Middleware;

use Closure;

class AnyRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        abort_unless($request->user()->hasAnyRole($roles), 403, 'Unauthorized');

        return $next($request);
    }
}
