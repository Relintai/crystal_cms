<?php

namespace App\Http\Controllers\ContentController;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\ContentDataImage;

class Image extends Controller implements ContentControllerInterface
{
	protected $id;
	protected $dbRow;
	protected $data;

	public function getId()
	{
		return $this->id;
	}

	public function constructFromDBRow($row)
	{
		$this->id = $row->id;
		$this->dbRow = $row;

		$data = ContentDataImage::find($row->content_id);

		if (!$data)
		{
			Log::critical("Image->constructFromDBRow: content data doesn't exists! Row: " . $row->toJson());
		}

		$this->data = $data;
	}

	public function getSettingsHTML()
	{
		return "";
	}

	public function getHTML()
	{
		return Theme::SiteView('content/image', ['imagedata' => $this->data, 'dbrow' => $this->dbRow], true);
	}

	public function createDBStructure($page_id)
	{
		$data = new ContentDataImage();

		$data->alt = "";
		$data->image_small = "";
		$data->image_full = "";
		$data->save();

		return $data->id;
	}

	public function deleteDBStructure($page_contents_row)
	{
		$data = ContentDataImage::find($page_contents_row->content_id);

		if (!$data)
		{
			return "Image->deleteDBStructure->ContentDataText::find($page_contents_row->content_id) returned null!";
		}

		//TODO: as the db entries are only soft deleted, maybe this could be stored as a revision

		$filesystem = new \Illuminate\Filesystem\Filesystem();

   		if ($filesystem->exists(public_path() . "/img/images/full/" . $data->image_full))
   		{
   			$filesystem->delete(public_path() . "/img/images/full/" . $data->image_full);
   		}

   		if ($filesystem->exists(public_path() . "/img/images/small/" . $data->image_small))
   		{
   			$filesystem->delete(public_path() . "/img/images/full/" . $data->image_small);
   		}

		$data->delete();
		return $data->id;
	}
}
