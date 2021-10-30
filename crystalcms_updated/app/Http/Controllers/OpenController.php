<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Theme;
use Config;


use App\Http\Requests;

class OpenController extends Controller
{
    public function index()
    {
    	return Theme::SiteView('openpage');
    }
}
