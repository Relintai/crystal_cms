<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RBACPermissions extends Model
{
	use SoftDeletes;

	public static $permissions = array(
		'all', 'none', 'edit', 'view', 'redirect',
		'add',
		);

    protected $table = 'rbac_permissions';
    protected $dates = ['deleted_at'];

    public static function rbac_array_contains($array, $element)
	{
		if (count($array) > 0)
		{
			foreach ($array as $r)
			{
				if ($r == $element)
				{
					return true;
				}
			}
		}

		return false;
	}
}
