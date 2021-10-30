<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GlobalSettings extends Model
{
	use SoftDeletes;

    protected $table = 'global_settings';
    protected $dates = ['deleted_at'];
}
