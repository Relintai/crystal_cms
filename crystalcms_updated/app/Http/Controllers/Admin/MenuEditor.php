<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Menu;
use App\Models\Pages;

class MenuEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $menu = Menu::orderBy('sort_order', 'asc')->get();
        
        $info = $request->session()->get('info', false);

        return Theme::AdminView('menu_editor/list', ['menuentries' => $menu, 'info' => $info]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Theme::AdminView('menu_editor/editcreate', ['menuentry' => 0, 'id' => 0]);
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
            'name_key' => 'required|max:40',
            'url' => 'required|max:60',
        ]);

        $me = Menu::where('url', $request->input('url'))->get();

        if (count($me) > 0)
        {
            return redirect()->back()->withErrors(trans('errors.menu_url_already_exists'))->withInput();
        }

        $menuentry = null;
        $order = 0;
        if ($request->input('id'))
        {
            $menuentry = Menu::find($request->input('id'));
        }
        else
        {
            $menuentry = new Menu();
            $e = Menu::orderBy('sort_order', 'desc')->take(1)->get();

            if ($e->count() > 0)
            {
                $order = $e[0]->sort_order + 1;
            }


            $p = Pages::where('url', $request->input('url'))->get();

            if ($request->input('create_page'))
            {
                if (count($p) == 0)
                {
                    $page = new Pages();
                    $page->name = $request->input('name_key');
                    $page->url = $request->input('url');
                    $page->save();
                }
                else
                {
                    return redirect()->back()->withErrors(trans('errors.page_url_already_exists'))->withInput();
                }
            }
            
        }

        $menuentry->name_key = $request->input('name_key');
        $menuentry->url = $request->input('url');

        if ($order > 0)
        {
            $menuentry->sort_order = $order;
        }

        $menuentry->save();

        $request->session()->flash('info', 'Add or Edit successful!');
        
        return redirect('admin/menu_editor');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $menuentry = Menu::findOrFail($id);

        return Theme::AdminView('menu_editor/editcreate', ['menuentry' => $menuentry, 'id' => $id]);
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

        $current = Menu::findOrFail($id);

        Log::info('MenuEntry deleted! Json: ' . $current->toJson());

        $current->delete();

        Menu::where('sort_order', '>', $current->sort_order)->decrement('sort_order', 1);

        return redirect()->back();
    }

    /**
     * Moves the entry up by one
     *
     * @param  int  $id
     * @return Response
     */
    public function up(Request $request)
    {
        $id = $request->input('id');

        if (!$id || !is_numeric($id))
        {
            return redirect()->back();
        }

        $current = Menu::findOrFail($id);

        if ($current->sort_order == 0)
        {
            Log::critical("MenuEditor->up->up operation on 0th element, either someone is probably messing with the forms, or a bug!");

            return redirect()->back();
        }

        $above = Menu::where('sort_order', $current->sort_order - 1)->get();

        if (count($above))
        {
            $a = $above[0];
            $a->sort_order += 1;
            $a->save();
        }
        else
        {
            Log::critical("MenuEditor->up: There were no element over the currently selected one! Somwhere remapping is broken! Incrementing sort_order anyway!");
        }

        $current->sort_order -= 1;
        $current->save();

        return redirect()->back();
    }

    /**
     *  Moves the entry down by one
     *
     * @param  int  $id
     * @return Response
     */
    public function down(Request $request)
    {
        $id = $request->input('id');

        if (!$id || !is_numeric($id))
        {
            return redirect()->back();
        }

        $current = Menu::findOrFail($id);

        $max = Menu::orderBy('sort_order', 'desc')->take(1)->get();

        if (count($max) && $max[0]->sort_order == $current->sort_order)
        {
            Log::critical("MenuEditor->down->down operation on 0th element, either someone is probably messing with the forms, or a bug!");

            return redirect()->back();
        }

        $below = Menu::where('sort_order', $current->sort_order + 1)->get();

        if (count($below))
        {
            $b = $below[0];
            $b->sort_order -= 1;
            $b->save();
        }
        else
        {
            Log::critical("MenuEditor->down: There were no element under the currently selected one! Somwhere remapping is broken! decrementind sort_order anyway!");
        }

        $current->sort_order += 1;
        $current->save();

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
