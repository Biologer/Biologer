<?php

namespace App\Http\Middleware;

use Closure;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\LanguageNegotiator;

class HeaderLocale
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
        if ($request->hasHeader('Accept-Language')) {
            LaravelLocalization::setLocale($this->negotiateLanguage($request));
        }

        return $next($request);
    }

    protected function negotiateLanguage($request)
    {
        return (new LanguageNegotiator(
            LaravelLocalization::getDefaultLocale(),
            LaravelLocalization::getSupportedLocales(),
            $request
        ))->negotiateLanguage();
    }
}
