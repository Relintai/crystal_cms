<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Menu;

class MenuDataSharer
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
        $menu = Menu::orderBy('sort_order', 'asc')->get();

        view()->share('menu', $menu);

        return $next($request);
    }
}
