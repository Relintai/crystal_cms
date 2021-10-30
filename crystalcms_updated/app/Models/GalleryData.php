<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GalleryData extends Model
{
	use SoftDeletes;

    protected $table = 'gallery_data';
    protected $dates = ['deleted_at'];
}
