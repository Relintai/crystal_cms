<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Theme;
use Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;

class Register extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $r = $request->settings->get('registration_enabled', false);
        if (!$r)
        {
            abort(404);
        }

        return Theme::SiteView('register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $r = $request->settings->get('registration_enabled', false);
        if (!$r)
        {
            abort(404);
        }


        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,name|alpha_num|between:5,20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|between:5,30',
            'password2' => 'required|same:password',
            'eula' => 'accepted'
        ]);

        if ($validator->fails()) {
            return redirect('register')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            $user = new User();

            $user->name = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Config::get('security.password')->hashPassword($request->input('password'));

            $rank = $request->settings->get('register_default_user_rank', -1);

            if ($rank == -1)
            {
                Log::critical('Register->store: register_default_user_rank GlobalSetting is bad!');
                abort(503);
            }

            $user->rbac_rank = $rank;
            $user->save();

            //let's log him in
            $sid = $user->generateSessionId();

            if ($sid) {
                $request->session()->put('sid', $sid);
                $user->save();

                return redirect('/');
            }

            return redirect('register')
                        ->withErrors('some internal error happened, try again later')
                        ->withInput();
        }
    }

}
