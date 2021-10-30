<?php

namespace App\Http\Controllers\ContentController;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\ContentDataText;

class Text extends Controller implements ContentControllerInterface
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

		$data = ContentDataText::find($row->content_id);

		if (!$data)
		{
			Log::critical("Text->constructFromDBRow: content data doesn't exists! Row: " . $row->toJson());
		}

		$this->data = $data;
	}

	public function getSettingsHTML()
	{
		return "";
	}

	public function getHTML()
	{
		return Theme::SiteView('content/text', ['textdata' => $this->data, 'dbrow' => $this->dbRow], true);
	}

	public function createDBStructure($page_id)
	{
		$data = new ContentDataText();

		$data->text = "";
		$data->nomod_text = "";
		$data->save();

		return $data->id;
	}

	public function deleteDBStructure($page_contents_row)
	{
		$data = ContentDataText::find($page_contents_row->content_id);

		if (!$data)
		{
			return "Text->deleteDBStructure->ContentDataText::find($page_contents_row->content_id) returned null!";
		}

		$data->delete();
		return $data->id;
	}
}
