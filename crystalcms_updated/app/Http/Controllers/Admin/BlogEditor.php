<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Config;
use Theme;
use CC;
use Log;
use App\Providers\ContentController\ContentControllerStatic;

use App\Constants\PageUrlContentIds;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\PageContents;
use App\Models\ContentDataBlog;
use App\Models\Blogs;
use App\Models\Pages;

class BlogEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $blogs = Blogs::all();
        $info = $request->input('info', false);

        return Theme::AdminView('blog_editor/blog_list', ['blogs' => $blogs, 'info' => $info]);
    }


    public function create(Request $request)
    {
        $info = $request->input('info', false);

        return Theme::AdminView('blog_editor/blog_edit_create', ['id' => null, 'blog' => null, 'info' => $info]);
    }

    public function show(Request $request, $id)
    {
        $blog = Blogs::findOrFail($id);

        $info = $request->input('info', false);

        return Theme::AdminView('blog_editor/blog_edit_create', ['id' => $id, 'blog' => $blog, 'info' => $info]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'integer',
            'name' => 'required|max:40',
            'url' => 'required|max:60',
        ]);

        $me = Blogs::where('url', $request->input('url'))->get();

        if (count($me) > 0)
        {
            return redirect()->back()->withErrors(trans('errors.blog_url_already_exists'))->withInput();
        }

        $pe = Pages::where('url', $request->input('url'))->get();

        if (count($pe) > 0)
        {
            return redirect()->back()->withErrors(trans('errors.blog_url_already_exists'))->withInput();
        }

        $blogentry = null;
        if ($request->input('id'))
        {
            $blogentry = Blogs::find($request->input('id'));
        }
        else
        {
            $blogentry = new Blogs();
        }

        $pageentry = null;
        if ($request->input('id'))
        {
            $pageentry = Pages::find($request->input('id'));
        }
        else
        {
            $pageentry = new Pages();
        }

        $blogentry->name = $request->input('name');
        $blogentry->url = $request->input('url');
        $blogentry->save();

        $pageentry->name = $request->input('name');
        $pageentry->url = $request->input('url');
        $pageentry->page_type = PageUrlContentIds::$CONTENT_BLOG;
        $pageentry->save();

        $request->session()->flash('info', 'Add or Edit successful!');
        
        return redirect('admin/blog_editor');
    }

    public function editContentData(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'blog_id' => 'required|integer',
        ]);

        $cdb = ContentDataBlog::findOrFail($request->input('id'));

        $blogid = $request->input('blog_id');

        if ($blogid != -1)
        {
            $blog = Blogs::findOrFail($blogid);
        }

        $cdb->blog_id = $blogid;

        $cdb->save();

        return redirect()->back();
    }
}
