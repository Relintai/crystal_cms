<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Theme;
use Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;

class Login extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Theme::SiteView('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'username' => 'required|alpha_num|between:5,20|exists:users,name',
            'password' => 'required|between:5,30',
        ]);

         if ($validator->fails()) {
            return redirect('login')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            $user = User::where('name', $request->input('username'))->get();

            if ($user[0]->password != Config::get('security.password')->hashPassword($request->input('password'))) {
                return redirect('login')
                        ->withErrors("Wrong username and/or password.")
                        ->withInput();
            }

            //log him in
            $request->session()->put('sid', $user[0]->sessionid);

            return redirect('/');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        $request->session()->pull('sid');
        return redirect('/');
    }

}
