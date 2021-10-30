<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Theme;
use Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;

class Admin extends Controller
{
    public function admin_panel() 
    {
        //return View(Theme::get('admin') . "admin_panel");
        return Theme::AdminView("admin_panel");
    }
}
