<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Log;
use Theme;
use Artisan;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class ArtisanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $info = $request->session()->get('info', false);

        return Theme::AdminView('artisan/artisan', ['info' => $info]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function doCommand(Request $request)
    {
        $command = $request->input('command', false);

        if (!$command)
        {
            Log::critical('Artisan: missing artisan command. Command: ' . $command);
            return redirect()->back()->with('info', trans('errors.bad_command'));
        }

        $c = explode(' ', $command);

        $arr = null;
        for ($i = 0; $i < count($c); $i++)
        {
            if ($i != 0 && isset($c[$i + 1]))
            {
                if ($c[$i + 1] === 'true')
                {
                    $c[$i + 1] = true;
                }

                $arr[$c[$i]] = $c[$i + 1];
            }
        }

        Log::critical('Running artisan command: ' . $command);

        $result = null;
        if ($arr)
            $result = Artisan::call($c[0], $arr);
        else
            $result = Artisan::call($c[0]);

        return redirect()->back()->with('info', trans('admin.artisan_result') . $result);
    }
}
