<?php

namespace App\Http\Middleware;

use Theme;
use Closure;

class GlobalSettingsMiddleware
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
        $settings = \App\Models\GlobalSettings::all();

        $request->settings = new \App\Data\Settings($settings);

        //load themes
        Theme::set($request->settings->get('theme_site'), $request->settings->get('theme_admin'));

        return $next($request);
    }
}
