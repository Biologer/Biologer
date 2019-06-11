<?php

namespace App\Http\Middleware;

use Closure;

class LocalizationPreferenceUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->user() && $request->user()->settings()->language !== app()->getLocale()) {
            $request->user()->settings()->language = app()->getLocale();
        }

        return $response;
    }
}
