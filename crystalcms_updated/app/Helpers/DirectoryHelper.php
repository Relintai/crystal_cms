<?php

namespace App\Helpers;

use Log;
use Normalizer;

class DirectoryHelper
{
	public $allowedImageExtensions = array('gif', 'jpeg', 'jpg', 'png', 'bmp', 'webp');

	public function getExtension($filename)
	{
		$a = explode('.', $filename);

		for ($i = count($a) - 1; $i >= 0; $i--)
		{
			if ($a[$i] !== "")
			{
				return strtolower($a[$i]);
			}
		}

		return null;
	}

	public function getUniqueDirectory($path, $hint, $fallback)
	{
		$name = null;
		if ($hint == "")
		{
			$name = $fallback;
		}
		else
		{
			$name = $hint;
		}

		$name = str_replace(" ", "_", $name);
		$name = Normalizer::normalize($name);

		//some characters, I think shouldn't be there
		$name = str_replace('*', '', $name);
		$name = str_replace('"', '', $name);
		$name = str_replace('/', '', $name);
		$name = str_replace('\\', '', $name);
		$name = str_replace('[', '', $name);
		$name = str_replace(']', '', $name);
		$name = str_replace(':', '', $name);
		$name = str_replace(';', '', $name);
		$name = str_replace('|', '', $name);
		$name = str_replace(',', '', $name);
		$name = str_replace('?', '', $name);
		$name = str_replace('+', '', $name);
		$name = str_replace('!', '', $name);

		$name = $this->normalizeChars($name);

		$filesystem = new \Illuminate\Filesystem\Filesystem();

		$i = -1;
		do {
			$i++;
		} while($filesystem->exists($path . $name . $i));

		return $name . $i;
	}

	public function getUniqueImageFileName($path, $extension, $hint, $fallback)
	{
		$name = null;
		if ($hint == "")
		{
			$name = $fallback;
		}
		else
		{
			$name = $hint;
		}

		$name = str_replace(" ", "_", $name);
		$name = Normalizer::normalize($name);

		//some characters, I think shouldn't be there
		$name = str_replace('*', '', $name);
		$name = str_replace('"', '', $name);
		$name = str_replace('/', '', $name);
		$name = str_replace('\\', '', $name);
		$name = str_replace('[', '', $name);
		$name = str_replace(']', '', $name);
		$name = str_replace(':', '', $name);
		$name = str_replace(';', '', $name);
		$name = str_replace('|', '', $name);
		$name = str_replace(',', '', $name);
		$name = str_replace('?', '', $name);
		$name = str_replace('+', '', $name);
		$name = str_replace('!', '', $name);

		$name = $this->normalizeChars($name);

		$filesystem = new \Illuminate\Filesystem\Filesystem();

		$i = -1;
		do {
			$i++;
		} while ($filesystem->exists($path . $name . $i . '.' . $extension));

		return $name . $i . '.' . $extension;
	}

	/**
	* This is important, because without this check uploading a gif with added php code
	* at the end can be run if the extension stays php
	*/
	public function isImageExtensionAllowed($extension) {
		foreach ($this->allowedImageExtensions as $a)
		{
			if ($a == $extension)
			{
				return true;
			}
		}

		return false;
	}

	public function sanitizeFolderName($folder)
	{
		$folder = str_replace(" ", "_", $folder);
		$folder = Normalizer::normalize($folder);

		//some characters, I think shouldn't be there
		$folder = str_replace('*', '', $folder);
		$folder = str_replace('"', '', $folder);
		$folder = str_replace('/', '', $folder);
		$folder = str_replace('\\', '', $folder);
		$folder = str_replace('[', '', $folder);
		$folder = str_replace(']', '', $folder);
		$folder = str_replace(':', '', $folder);
		$folder = str_replace(';', '', $folder);
		$folder = str_replace('|', '', $folder);
		$folder = str_replace(',', '', $folder);
		$folder = str_replace('?', '', $folder);
		$folder = str_replace('+', '', $folder);
		$folder = str_replace('!', '', $folder);

		$folder = $this->normalizeChars($folder);

		return $folder;
	}

	//this is from http://stackoverflow.com/questions/3371697/replacing-accented-characters-php
	public static function normalizeChars($s) {
	   	$replace = array(
	        '??'=>'-', '??'=>'-', '??'=>'-', '??'=>'-',
	        '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'Ae',
	        '??'=>'B',
	        '??'=>'C', '??'=>'C', '??'=>'C',
	        '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'E',
	        '??'=>'G',
	        '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'I',
	        '??'=>'L',
	        '??'=>'N', '??'=>'N',
	        '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'Oe',
	        '??'=>'S', '??'=>'S', '??'=>'S', '??'=>'S',
	        '??'=>'T',
	        '??'=>'U', '??'=>'U', '??'=>'U', '??'=>'Ue',
	        '??'=>'Y',
	        '??'=>'Z', '??'=>'Z', '??'=>'Z',
	        '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'ae', '??'=>'ae', '??'=>'ae', '??'=>'ae',
	        '??'=>'b', '??'=>'b', '??'=>'b', '??'=>'b',
	        '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'ch', '??'=>'ch',
	        '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'D', '??'=>'d',
	        '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e',
	        '??'=>'f', '??'=>'f', '??'=>'f',
	        '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g',
	        '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h',
	        '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'ij', '??'=>'ij',
	        '??'=>'j', '??'=>'j', '??'=>'j', '??'=>'j', '??'=>'ja', '??'=>'ja', '??'=>'je', '??'=>'je', '??'=>'jo', '??'=>'jo', '??'=>'ju', '??'=>'ju',
	        '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k',
	        '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l',
	        '??'=>'m', '??'=>'m', '??'=>'m', '??'=>'m',
	        '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n',
	        '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'oe', '??'=>'oe', '??'=>'oe',
	        '??'=>'p', '??'=>'p', '??'=>'p', '??'=>'p',
	        '??'=>'q',
	        '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r',
	        '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'sch', '??'=>'sch', '??'=>'sh', '??'=>'sh', '??'=>'ss',
	        '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '???'=>'tm',
	        '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'ue',
	        '??'=>'v', '??'=>'v', '??'=>'v',
	        '??'=>'w', '??'=>'w', '??'=>'w',
	        '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y',
	        '??'=>'y', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'zh', '??'=>'zh'
	    );

    	return strtr($s, $replace);
	}
}
