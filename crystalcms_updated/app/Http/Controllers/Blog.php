<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use Theme;
use Log;
use CC;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use App\Models\PageContents;
use App\Models\BlogEntries;
use App\Models\Blogs;


class Blog extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page, $page_url, $args = null)
    {
        $blogs = Blogs::where('url', $page_url)->first();

        if (!$blogs)
        {
        	Log::error("Blog->index: Blog doesn't exists, but the pages link to it does! $page_url: " . $page_url);
        	abort(404);
        }

        $info = $request->session()->get('info', false);

        $entries = BlogEntries::where('blog_id', $blogs->id)->get();

        return Theme::SiteView('blog', [
                        'blogs' => $blogs, 
                        'entries' => $entries,
                        'page' => $page, 
                        'info' => $info]);
    }

    public function indexRoot(Request $request)
    {
        $p = Pages::orderBy('id', 'asc')->first();
        
        $url = '';
        if ($p)
        {
            $url = $p->url;
        }

        return $this->index($request, $p, $p->url, null);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }}
