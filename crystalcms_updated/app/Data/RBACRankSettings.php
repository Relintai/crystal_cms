<?php

namespace App\Data;

class RBACRankSettings
{
	public $settings;

	public function __construct($s)
	{
		$this->settings = null;
		$sett = explode(',', $s);
		if (count($sett))
		{
			foreach ($sett as $row)
			{
				if ($row)
				{
					$this->settings[] = $row;
				}
			}
		}
	}

	public function get($setting)
	{
		if (count($this->settings))
		{
			foreach ($this->settings as $r)
			{
				if ($r === $setting)
				{
					return true;
				}
			}
		}
		return false;
	}
}
