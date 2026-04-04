<?php

namespace App\Http\Middleware;

use Closure;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ClearLocaleOnDefault
{
    public function handle($request, Closure $next)
    {
        $urlLocale = LaravelLocalization::getCurrentLocale();
        $defaultLocale = LaravelLocalization::getDefaultLocale();

        if ($urlLocale === $defaultLocale) {
            session()->forget('locale');
            \Cookie::queue(\Cookie::forget('locale'));
        }

        $response = $next($request);

        if ($urlLocale === $defaultLocale) {
            $currentLocale = app()->getLocale();

            session()->put('locale', $currentLocale);

            $cookie = cookie('locale', $currentLocale, 60 * 24 * 365, '/', null, $request->isSecure(), false);
            $response->headers->setCookie($cookie);

            if ($request->user()) {
                $settings = $request->user()->settings();
                if ($settings->language !== $currentLocale) {
                    $settings->language = $currentLocale;
                }
            }
        }

        return $response;
    }
}
