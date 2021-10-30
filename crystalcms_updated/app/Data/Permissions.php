<?php

namespace App\Data;

use Log;
use App\Models\RBACPermissions;

class Permissions
{
	public $permissions;

	public function __construct()
	{
		$this->permissions = null;

		$p = RBACPermissions::$permissions;

		foreach ($p as $perm)
		{
			$this->permissions[$perm] = false;
		}
	}

	public function applyPermissions($permissionRow)
	{
		$perm = explode(',', $permissionRow->permissions);

		foreach ($perm as $p)
		{
			if ($p == 'all')
			{
				$this->setAll();
				return;
			}

			if ($p == 'none')
			{
				$this->setNone();
				return;
			}

			if (!isset($this->permissions[$p]))
			{
				Log::critical("Permissions: A row has a permission that doesn't exists! permission: " . $p);
				continue;
			}

			if (!$permissionRow->revoke) 
			{
				$this->permissions[$p] = true;
			}
			else
			{
				$this->permissions[$p] = false;
			}
		}

	}

	public function get($key, $default = null)
	{
		if (isset($this->permissions[$key]))
		{
			return $this->permissions[$key];
		}

		return $default;
	}

	protected function setAll()
	{
		foreach ($this->permissions as $key => $value)
		{
			$this->permissions[$key] = true;
		}
	}

	protected function setNone()
	{
		foreach ($this->permissions as $key => $value)
		{
			$this->permissions[$key] = false;
		}
	}
}
