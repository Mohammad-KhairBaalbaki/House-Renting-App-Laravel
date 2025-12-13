<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApiLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->query('lang');

        if (! $locale) {
            $locale = $request->header('Accept-Language');
        }

        if (! in_array($locale, ['ar', 'en'])) {
            $locale = config('app.locale', 'en'); 
        }

        App::setLocale($locale);

        return $next($request);
    }
}
