<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PageContents extends Model
{
	use SoftDeletes;

    protected $table = 'page_contents';
    protected $dates = ['deleted_at'];
}
