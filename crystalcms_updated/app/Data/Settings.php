<?php

namespace App\Data;

class Settings
{
	public $settings;

	public function __construct($s)
	{
		$this->settings = null;
		if (count($s))
		{
			foreach ($s as $row)
			{
				$this->settings[$row->key] = $row->value;
			}
		}
	}

	public function get($key, $default = null)
	{
		if (isset($this->settings[$key]))
		{
			return $this->settings[$key];
		}

		return $default;
	}
}
