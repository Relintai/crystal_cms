<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use Config;
use Theme;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LocaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $locale)
    {
        $v = Config::get('locales.' . $locale);

        if (!$v)
        {
            Log::warning('LocaleController->index: Bad locale! locale: ' . $locale);
            return redirect()->back();
        }

        $request->session()->put('locale', $locale);

        //return redirect()->back();
        return redirect('/');
    }
}
