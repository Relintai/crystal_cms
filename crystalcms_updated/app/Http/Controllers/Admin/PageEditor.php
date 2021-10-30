<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Theme;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Pages;

class PageEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $pages = Pages::all();
        
        $info = $request->session()->get('info', false);

        return Theme::AdminView('page_editor/list', ['pages' => $pages, 'info' => $info]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Theme::AdminView('page_editor/editcreate', ['page' => 0, 'id' => 0]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'integer',
            'name' => 'required|max:40',
            'url' => 'required|max:60',
        ]);

        $page = null;
        $order = 0;
        if ($request->input('id'))
        {
            $url = $request->input('url');

            $u = Pages::where('url', $url)->get();

            if (count($u))
            {
                if ($u[0]->id != $request->input('id'))
                {
                    return redirect()->back()->withErrors(trans('errors.page_url_already_exists'));
                }
            }

            $page = Pages::findOrFail($request->input('id'));
        }
        else
        {
            $url = $request->input('url');

            $u = Pages::where('url', $url)->get();

            if (count($u))
            {
                return redirect()->back()->withErrors(trans('errors.page_url_already_exists'));
            }

            $page = new Pages();
        }

        $page->name = $request->input('name');
        $page->url = $request->input('url');

        $page->save();

        $request->session()->flash('info', 'Add or Edit successful!');
        
        return redirect('admin/page_editor');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $page = Pages::findOrFail($id);

        return Theme::AdminView('page_editor/editcreate', ['page' => $page, 'id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!$id || !is_numeric($id))
        {
            return redirect()->back();
        }

        $current = Pages::findOrFail($id);

        Log::info('Page soft deleted! Json: ' . $current->toJson());

        $current->delete();
        
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }
}
