<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BlogEntries extends Model
{
	use SoftDeletes;

    protected $table = 'blog_entries';
    protected $dates = ['deleted_at'];
}
