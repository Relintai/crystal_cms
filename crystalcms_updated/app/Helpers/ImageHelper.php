<?php

namespace App\Helpers;

use Log;

class ImageHelper
{
	protected $image;
	protected $imageSize;
	protected $path;
	protected $errors;

	public function loadImage($path, $extension = null)
	{
		$this->path = $path;

		$this->imageSize = getimagesize($path);

		switch ($this->imageSize[2])
		{
			case IMG_GIF:
				$this->image = imagecreatefromgif($path);
				break;
			case IMG_JPG:
			case IMG_JPEG:
				$this->image = imagecreatefromjpeg($path);
				break;
			case 3: //IMG_PNG is 4, but pngs give back 3 :/
			case IMG_PNG:
				$this->image = imagecreatefrompng($path);
				break;
			default:
				$this->errors[] = "Image loading failed, image type is'nt supported!";
				return;
		}
	}

	public function makeThumbnail($size, $path, $extension)
	{
		if (!$this->isLoaded())
		{
			$this->errors[] = "Image isn't loaded!";
			return false;
		}

		$cutout = null;

		if ($this->getWidth() < $this->getHeight())
		{
			$cutout['x'] = 0;
			$cutout['y'] = intval(($this->getHeight() - $this->getWidth()) / 2);
			$cutout['width'] = $this->getWidth();
			$cutout['height'] = $this->getWidth();
		}
		else if ($this->getWidth() > $this->getHeight())
		{
			$cutout['x'] = intval(($this->getWidth() - $this->getHeight()) / 2);
			$cutout['y'] = 0;
			$cutout['width'] = $this->getHeight();
			$cutout['height'] = $this->getHeight();
		}
		else
		{
			//they are equal
			$cutout['x'] = 0;
			$cutout['y'] = 0;
			$cutout['width'] = $this->getWidth();
			$cutout['height'] = $this->getHeight();
		}

		$new_image = imagecreatetruecolor($size, $size);

		$resampl = imagecopyresampled($new_image, $this->image,
						0, 0, $cutout['x'], $cutout['y'],
						$size, $size,
						$cutout['width'], $cutout['height']);

		if (!$resampl)
		{
			$this->errors[] = "Image resampling failed!";
			return false;
		}

		switch ($extension)
		{
			case 'gif':
				imagegif($new_image, $path);
				break;
			case 'jpg':
			case 'jpeg':
				imagejpeg($new_image, $path);
				break;
			case 'png':
				imagepng($new_image, $path);
				break;
			default:
				$this->errors[] = "shrinkKeepAspectRatioSave: saving->Unsupported extension!";
				return false;
		}

		return true;
	}

	public function shrinkKeepAspectRatioSave($new_width, $path, $extension)
	{
		if (!$this->isLoaded())
		{
			$this->errors[] = "Image isn't loaded!";
			return false;
		}

		$ar = $new_width / $this->getWidth();
		$new_height = intval($this->getHeight() * $ar);
		$new_image = imagecreatetruecolor($new_width, $new_height);

		$resampl = imagecopyresampled($new_image, $this->image,
						0, 0, 0, 0,
						$new_width, $new_height,
						$this->getWidth(), $this->getHeight());

		if (!$resampl)
		{
			$this->errors[] = "Image resampling failed!";
			return false;
		}

		switch ($extension)
		{
			case 'gif':
				imagegif($new_image, $path);
				break;
			case 'jpg':
			case 'jpeg':
				imagejpeg($new_image, $path);
				break;
			case 'png':
				imagepng($new_image, $path);
				break;
			default:
				$this->errors[] = "shrinkKeepAspectRatioSave: saving->Unsupported extension!";
				return false;
		}

		return true;
	}

	public function isLoaded()
	{
		if ($this->image)
		{
			return true;
		}

		return false;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function getWidth()
	{
		return $this->imageSize[0];
	}

	public function getHeight()
	{
		return $this->imageSize[1];
	}

	public function getExtensionFromPath($path)
	{
		$p = explode('.', $path);

		if (!count($p))
		{
			return false;
		}

		return $p[count($p) - 1];
	}
}
