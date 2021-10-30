<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ContentDataText extends Model
{
	use SoftDeletes;

    protected $table = 'content_data_text';
    protected $dates = ['deleted_at'];
}
