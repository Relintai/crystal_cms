<?php

namespace App\Http\Controllers\ContentController;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\ContentDataBlog;
use App\Models\BlogEntries;
use App\Models\Blogs;

class Blog extends Controller implements ContentControllerInterface
{
	protected $id;
	protected $dbRow;
	protected $contentData;
	protected $blogEntries;

	public function getId()
	{
		return $this->id;
	}

	public function constructFromDBRow($row)
	{
		$this->id = $row->id;
		$this->dbRow = $row;

		$this->contentData = ContentDataBlog::find($row->content_id);

		if (!$this->contentData)
		{
			Log::critical("Gallery->constructFromDBRow: content data doesn't exists! Row: " . $row->toJson());
		}

		$this->blogEntries = BlogEntries::where('blog_id', $this->contentData->id)->orderBy('created_at', 'desc')->take(5)->get();
	}

	public function getSettingsHTML()
	{
		$blogs = Blogs::all();

		$arr[-1] = "None" ;
		foreach ($blogs as $b)
		{
			$arr[$b->id] = $b->name;
		}

		return Theme::AdminView('blog_editor/blog_page_settings', ['id' => $this->contentData->id, 'blogs' => $arr, 'selected' => $this->contentData->blog_id]);
	}

	public function getHTML()
	{
		return Theme::SiteView('content/blog', [
			'contentdata' => $this->contentData, 
			'dbrow' => $this->dbRow, 
			'blogentries' => $this->blogEntries]);
	}

	public function createDBStructure($page_id)
	{
		$data = new ContentDataBlog();

		$data->blog_id = -1;
		$data->save();

		return $data->id;
	}

	public function deleteDBStructure($page_contents_row)
	{
		$data = ContentDataBlog::find($page_contents_row->content_id);

		if (!$data)
		{
			return "Blog->deleteDBStructure->ContentDataText::find($page_contents_row->content_id) returned null!";
		}

		ContentDataBlog::where('blog_id', $data->id)->delete();

		return $data->id;
	}
}
