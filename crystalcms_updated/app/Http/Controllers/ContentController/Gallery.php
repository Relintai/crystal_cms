<?php

namespace App\Http\Controllers\ContentController;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\ContentDataGallery;
use App\Models\GalleryData;

class Gallery extends Controller implements ContentControllerInterface
{
	protected $id;
	protected $dbRow;
	protected $contentData;
	protected $images;

	public function getId()
	{
		return $this->id;
	}

	public function constructFromDBRow($row)
	{
		$this->id = $row->id;
		$this->dbRow = $row;

		$this->contentData = ContentDataGallery::find($row->content_id);

		if (!$this->contentData)
		{
			Log::critical("Gallery->constructFromDBRow: content data doesn't exists! Row: " . $row->toJson());
		}

		$this->images = GalleryData::where('gallery_id', $this->contentData->id)->orderBy('sort_order', 'asc')->get();
	}

	public function getSettingsHTML()
	{
		return "";
	}

	public function getHTML()
	{
		return Theme::SiteView('content/gallery', [
			'contentdata' => $this->contentData, 
			'dbrow' => $this->dbRow, 
			'images' => $this->images], true);
	}

	public function createDBStructure($page_id)
	{
		$data = new ContentDataGallery();

		$data->name = "";
		$data->folder = "";
		$data->description = "";
		$data->save();

		return $data->id;
	}

	public function deleteDBStructure($page_contents_row)
	{
		$data = ContentDataGallery::find($page_contents_row->content_id);

		if (!$data)
		{
			return "Gallery->deleteDBStructure->ContentDataText::find($page_contents_row->content_id) returned null!";
		}

		GalleryData::where('gallery_id', $data->id)->delete();

		//TODO Deleting the images is a bad idea, howewes they should be stored in a different dir
		return $data->id;
	}
}
