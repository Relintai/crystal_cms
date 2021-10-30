<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pages extends Model
{
	use SoftDeletes;

    protected $table = 'pages';
    protected $dates = ['deleted_at'];
}
