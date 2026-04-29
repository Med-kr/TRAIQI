<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = array_keys(config('traiqi.locales', []));
        $fallbackLocale = config('app.fallback_locale', 'en');
        $locale = $request->cookie('traiqi_locale', config('app.locale'));

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = $fallbackLocale;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
