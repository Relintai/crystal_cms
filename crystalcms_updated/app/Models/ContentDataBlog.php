<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ContentDataBlog extends Model
{
	use SoftDeletes;

    protected $table = 'content_data_blog';
    protected $dates = ['deleted_at'];
}
