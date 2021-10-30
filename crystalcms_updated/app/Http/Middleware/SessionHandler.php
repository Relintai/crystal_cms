<?php

namespace App\Http\Middleware;

use App;
use Config;
use Closure;
use App\Models\User;

class SessionHandler
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
        $p = explode("/", $request->path());

        if (count($p) == 0 || $p[0] != "language")
        {
            if ($request->session()->get('locale', false))
            {
                $locale = $request->session()->get('locale', false);

                $v = Config::get('locales.' . $locale);

                if (!$v)
                {
                    //just pull it, the default locale will be set automatically
                    $request->session()->pull('locale', false);

                    $red = config('session.no_locale_redirect');

                    if ($red)
                    {
                        if (!App::environment('testing'))
                        {
                            return redirect($red);
                        }
                    }
                }
                else
                {
                    //locale exists, just set it
                    App::setLocale($locale);
                }
            }
            else
            {
                $red = config('session.no_locale_redirect');

                if ($red)
                {
                    if (!App::environment('testing'))
                    {
                        return redirect('/language');
                    }
                }
            }
        }

        if ($request->session()->get('sid', false)) {
            $sid = $request->session()->get('sid');

            $user = User::where('sessionid', $sid)->get();

            if (isset($user[0])) {
                $request->userdata = $user[0];
                view()->share('userdata', $user[0]);
            } else {
                $request->session()->pull('sid', false);
                view()->share('userdata', false);
            }
        }
        else
        {
            view()->share('userdata', false);
        }

        return $next($request);
    }
}
