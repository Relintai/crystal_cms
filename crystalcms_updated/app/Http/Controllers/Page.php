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

class Page extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page, $page_url, $args = null)
    {
        $pc = PageContents::where('page_id', $page->id)
                ->where(function ($query) {
                    $query->where('locale', App::getLocale())
                    ->orWhere('locale', 'all');
                })
                ->orderBy('sort_order', 'asc')
                ->get();

        $info = $request->session()->get('info', false);

        $contents = CC::createContentArray($pc);

        return Theme::SiteView('page', ['contents' => $contents, 
                        'page_contents' => $pc, 
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
    }
}
