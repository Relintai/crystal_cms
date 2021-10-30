<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class PageUrls extends Model
{
	use SoftDeletes;

    protected $table = 'page_urls';
    protected $dates = ['deleted_at'];
}
