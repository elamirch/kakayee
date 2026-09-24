<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locales = config('gamification.locales', ['en']);
        $locale = $request->query('lang') ?: $request->header('Accept-Language');

        if (! in_array($locale, $locales, true)) {
            $locale = config('gamification.default_locale', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
