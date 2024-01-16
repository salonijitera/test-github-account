<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle localization from request header
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        if ($locale = $request->header('Accept-Language')) {
            if (in_array($locale, ['en', 'ja', 'es', 'de', 'fr', 'it'])) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
