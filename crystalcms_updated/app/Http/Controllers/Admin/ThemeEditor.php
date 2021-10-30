<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Theme;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

use App\Models\GlobalSettings;

class ThemeEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $site = $request->settings->get('theme_site', false);
        $admin = $request->settings->get('theme_admin', false);

        if (!$site)
        {
            $site = 'null';
        }

        if (!$admin)
        {
            $admin = 'null';
        }

        $fs = new \Illuminate\Filesystem\Filesystem();

        $sitef = $fs->directories(base_path('resources/views/site'));

        $site_themes = null;
        foreach ($sitef as $s)
        {
            $e = explode(DIRECTORY_SEPARATOR, $s);
            $site_themes[$e[count($e) - 1]] = $e[count($e) - 1];
        }

        $adminf = $fs->directories(base_path('resources/views/admin'));

        $admin_themes = null;
        foreach ($adminf as $s)
        {
            $e = explode(DIRECTORY_SEPARATOR, $s);
            $admin_themes[$e[count($e) - 1]] = $e[count($e) - 1];
        }

        $info = $request->input('info', false);

        return Theme::AdminView('theme_editor/selector', [
                'site' => $site,
                'site_themes' => $site_themes,
                'admin' => $admin, 
                'admin_themes' => $admin_themes,
                'info' => $info]);
    }

    public function store(Request $request)
    {
        $site = $request->input('site', false);
        $admin = $request->input('admin', false);

        $fs = new \Illuminate\Filesystem\Filesystem();

        $sitef = $fs->directories(base_path('resources/views/site'));

        $site_themes = null;
        foreach ($sitef as $s)
        {
            $e = explode(DIRECTORY_SEPARATOR, $s);
            $site_themes[$e[count($e) - 1]] = $e[count($e) - 1];
        }

        $adminf = $fs->directories(base_path('resources/views/admin'));

        $admin_themes = null;
        foreach ($adminf as $s)
        {
            $e = explode(DIRECTORY_SEPARATOR, $s);
            $admin_themes[$e[count($e) - 1]] = $e[count($e) - 1];
        }

        $f1 = false;
        foreach ($site_themes as $s)
        {
            if ($s === $site)
            {
                $f1 = true;
                break;
            }
        }

        $f2 = false;
        foreach ($admin_themes as $s)
        {
            if ($s === $admin)
            {
                $f2 = true;
                break;
            }
        }

        if (!$f1)
        {
            Log::warning("ThemeEditor->store: Selected site theme doesn't exists! theme: " . $site);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        if (!$f2)
        {
            Log::warning("ThemeEditor->store: Selected admin theme doesn't exists! theme: " . $admin);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        GlobalSettings::where('key', 'theme_site')->update(['value' => $site]);
        GlobalSettings::where('key', 'theme_admin')->update(['value' => $admin]);

        return redirect('admin/theme_editor');
    }
}
