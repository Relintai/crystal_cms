<?php

namespace App\Providers\Theme;

use Log;

class ThemeProviderSingleton
{
	public static $themes = [
		'admin' => "admin/default/",
		'site' => "site/default/",
	];

	public static $defaultThemes = [
		'admin' => "admin/default/",
		'site' => "site/default/",
	];

	public static function set($site, $admin)
	{
		if ($site != null || $site != "")
		{
			ThemeProviderSingleton::$themes['site'] = 'site/' . $site . '/';
		}

		if ($admin != null || $admin != "")
		{
			ThemeProviderSingleton::$themes['admin'] = 'admin/' . $admin . '/';
		}
	}

	//Deprecated
	protected static function get($theme = "site")
	{
		if ($theme != "site" && $theme != "admin")
		{
			Log::critical("ThemeProviderSingleton->get: theme isn't site or admin reverting to site! Theme: " . $theme);
			$theme = "site";
		}

		return ThemeProviderSingleton::$themes[$theme];
	}

	public static function getThemes()
	{
		return ThemeProviderSingleton::$themes;
	}

	public static function AdminView($viewName, $array = [])
	{

		$content = null;
		if (file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['admin'] . $viewName . '.blade.php'))
		{
			$content = View(ThemeProviderSingleton::$themes['admin'] . $viewName, $array);
		}
		else
		{
			$content = View(ThemeProviderSingleton::$defaultThemes['admin'] . $viewName, $array);
		}

		$header = null;
		if (file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['admin'] . 'admin_header.blade.php'))
		{
			$header = View(ThemeProviderSingleton::$themes['admin'] . 'admin_header');
		}
		else
		{
			$header = View(ThemeProviderSingleton::$defaultThemes['admin'] . 'admin_header');
		}
		
		//dd(file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['admin'] . 'admin_main.blade.php'));
		if (file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['admin'] . 'admin_main.blade.php'))
		{
			return View(ThemeProviderSingleton::$themes['admin'] . 'admin_main', ['head' => $header, 'content' => $content]);
		}
		else
		{
			return View(ThemeProviderSingleton::$defaultThemes['admin'] . 'admin_main', ['head' => $header, 'content' => $content]);
		}
	}

	public static function SiteView($viewName, $array = [], $isPart = false)
	{
		$content = null;
		if (file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['site'] . $viewName . '.blade.php'))
		{
			$content = View(ThemeProviderSingleton::$themes['site'] . $viewName, $array);
		}
		else
		{
			$content = View(ThemeProviderSingleton::$defaultThemes['site'] . $viewName, $array);
		}

		if ($isPart)
		{
			return $content;
		}

		/*
		$header = null;
		if (file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['site'] . 'admin_header.blade.php'))
		{
			$header = View(ThemeProviderSingleton::$themes['site'] . 'header');
		}
		else
		{
			$header = View(ThemeProviderSingleton::$defaultThemes['site'] . 'header');
		}
		*/

		$header = "";
		if (array_key_exists("head", $array))
		{
			$header = $array["head"];
		}

		//dd(file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['site'] . 'admin_main.blade.php'));
		if (file_exists(realpath('./../') . '/resources/views/' . ThemeProviderSingleton::$themes['site'] . 'admin_main.blade.php'))
		{
			return View(ThemeProviderSingleton::$themes['site'] . 'main', ['head' => $header, 'content' => $content]);
		}
		else
		{
			return View(ThemeProviderSingleton::$defaultThemes['site'] . 'main', ['head' => $header, 'content' => $content]);
		}
	}

}