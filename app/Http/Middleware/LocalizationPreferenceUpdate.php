<?php

namespace App\Http\Middleware;

use Closure;

class LocalizationPreferenceUpdate
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->user()) {
            $currentLocale = app()->getLocale();
            $settings = $request->user()->settings();

            if ($settings->language !== $currentLocale) {
                $settings->language = $currentLocale;
            }

            session()->put('locale', $currentLocale);
            \Cookie::queue('locale', $currentLocale, 60 * 24 * 365);
        }

        return $response;
    }
}
