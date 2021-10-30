<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RBACGroups extends Model
{
	use SoftDeletes;

    protected $table = 'rbac_groups';
    protected $dates = ['deleted_at'];
}
