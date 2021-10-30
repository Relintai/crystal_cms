<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ImageUploads extends Model
{
	use SoftDeletes;

    protected $table = 'image_uploads';
    protected $dates = ['deleted_at'];
}
