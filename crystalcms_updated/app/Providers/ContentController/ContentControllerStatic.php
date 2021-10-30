<?php

namespace App\Providers\ContentController;

class ContentControllerStatic
{
	public static $content_types = [
		'text' => \App\Http\Controllers\ContentController\Text::class,
		'image' => \App\Http\Controllers\ContentController\Image::class,
		'gallery' => \App\Http\Controllers\ContentController\Gallery::class,
		'blog' => \App\Http\Controllers\ContentController\Blog::class,
	];

	public static $content_type_data = [
		'text' => [
			'contentcontroller' => 'text',
			'name' => 'contentcontroller.text',
			'description' => 'contentcontroller.text_desc',
		],
		'image' => [
			'contentcontroller' => 'image',
			'name' => 'contentcontroller.image',
			'description' => 'contentcontroller.image_desc',
		],
		'gallery' => [
			'contentcontroller' => 'gallery',
			'name' => 'contentcontroller.gallery',
			'description' => 'contentcontroller.gallery_desc',
		],
		'blog' => [
			'contentcontroller' => 'blog',
			'name' => 'contentcontroller.blog',
			'description' => 'contentcontroller.blog_desc',
		],
	];

	public static function createContentArray($contents)
	{
		$arr = null;

		if (count($contents) > 0)
		{
			foreach ($contents as $c)
			{
				$cc = new ContentControllerStatic::$content_types[$c->type];

				$cc->constructFromDBRow($c);

				$arr[] = $cc;
			}
		}

		return $arr;
	}

	public static function getContentController($name)
	{
		return new ContentControllerStatic::$content_types[$name];
	}

	public static function getContentData()
	{
		return ContentControllerStatic::$content_type_data;
	}

}