<?php

namespace App\Http\Middleware;

use Closure;

class AllRoles
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
        abort_unless($request->user()->hasAllRoles($roles), 403, 'Unauthorized');

        return $next($request);
    }
}
