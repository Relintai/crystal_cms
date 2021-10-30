<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Config;
use Theme;
use CC;
use Log;
use App\Providers\ContentController\ContentControllerStatic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\PageContents;
use App\Models\Pages;

class PageContentEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $pages = Pages::all();
        $info = $request->input('info', false);

        return Theme::AdminView('page_content_editor/list', ['pages' => $pages, 'info' => $info]);
    }

    public function show($page_id, Request $request)
    {
        if (!is_numeric($page_id))
        {
            return redirect('admin/page_content_editor');
        }

        //Let's check if the page exists
        $page = Pages::findOrFail($page_id);

        if (!$page)
        {
            return redirect('admin/page_content_editor');
        }

        $pc = PageContents::where('page_id', $page_id)->orderBy('sort_order', 'asc')->get();

        $info = $request->session()->get('info', false);

        $contents = CC::createContentArray($pc);

        $locales = Config::get('locales');
        $locales['all'] = 'all';

        return Theme::AdminView('page_content_editor/contentlist', [
                'locales' => $locales,
                'contents' => $contents, 
                'page_contents' => $pc, 
                'page_id' => $page_id, 
                'info' => $info]);
    }

    public function add($page_id)
    {
        if (!is_numeric($page_id))
        {
            return redirect('admin/page_content_editor');
        }

        //Let's check if the paghe exists
        $page = Pages::findOrFail($page_id);

        if (!$page)
        {
            return redirect('admin/page_content_editor');
        }

        return Theme::AdminView('page_content_editor/page_content_add_selector', ['page_id' => $page_id]);
    }

    public function add_store(Request $request)
    {
        $page_id = $request->input('page_id', false);
        $contentcontroller = $request->input('contentcontroller', false);

        if (!$page_id || !is_numeric($page_id))
        {
            Log::critical('PageContentEditor->add_store: page_id is bad, page_id: ' . $page_id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        if (!$contentcontroller || !isset(CC::getContentData()[$contentcontroller]))
        {
            Log::critical('PageContentEditor->add_store: contentcontroller is bad, contentcontroller: ' . $contentcontroller);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $controller = CC::getContentController($contentcontroller);

        $content_id = $controller->createDBStructure($page_id);
        
        if (!is_numeric($content_id))
        {
            Log::critical('PageContentEditor->add_store: contentController gave back an error, contentcontroller: ' . $contentcontroller . ' errors: ' . json_encode($content_id));
            return redirect()->back()->withErrors($content_id);
        }

        $so = 0;
        $pc = PageContents::where('page_id', $page_id)->get();

        if (count($pc))
        {
            foreach ($pc as $p)
            {
                if ($p->sort_order > $so)
                {
                    $so = $p->sort_order;
                }
            }

            $so++;
        }

        $page_contents = new PageContents();

        $page_contents->page_id = $page_id;
        $page_contents->type = $contentcontroller;
        $page_contents->content_id = $content_id;
        $page_contents->locale = Config::get('app.locale');;
        $page_contents->sort_order = $so;
        $page_contents->save();

        $request->session()->flash('info', trans(CC::getContentData()[$contentcontroller]['name']) . trans('admin.field_added_successfully'));

        return redirect('admin/page_content_editor/show/' . $page_id);
    }

    public function up(Request $request) 
    {
        $id = $request->input('id', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('PageContentEditor->up: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = PageContents::findOrFail($id);

        if ($current->sort_order == 0)
        {
            Log::critical('PageContentEditor->up: up is pressed, white sort order is 0!');
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $above = PageContents::where('page_id', $current->page_id)->where('sort_order', $current->sort_order - 1)->first();
        
        $above->sort_order += 1;
        $current->sort_order -= 1;

        $above->save();
        $current->save();

        return redirect()->back();
    }

    public function down(Request $request)
    {
        $id = $request->input('id', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('PageContentEditor->down: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = PageContents::findOrFail($id);

        $pc = PageContents::where('page_id', $current->page_id)->get();

        $max = $pc[0]->sort_order;
        for ($i = 1; $i < count($pc); $i++)
        {
            if ($pc[$i]->sort_order > $max)
            {
                $max = $pc[$i]->sort_order;
            }
        }

        if ($current->sort_order == $max)
        {
            Log::critical('PageContentEditor->down? Down is pressed, white sort order is the max (the entry is at the bottom)!');
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $below = PageContents::where('page_id', $current->page_id)->where('sort_order', $current->sort_order + 1)->first();
        
        $below->sort_order -= 1;
        $current->sort_order += 1;

        $below->save();
        $current->save();

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', false);
        $page_id = $request->input('page_id', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('PageContentEditor->delete: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        if (!$page_id || !is_numeric($page_id))
        {
            Log::critical('PageContentEditor->delete: page_id is bad, page_id: ' . $page_id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = PageContents::findOrFail($id);

        $controller = CC::getContentController($current->type);

        $result = $controller->deleteDBStructure($current);

        if (!is_numeric($result))
        {
            Log::critical('PageContentEditor->delete: $controller->deleteDBStructure() returned with error: ' . json_encode($result));
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current->delete();

        $bigger = PageContents::where('sort_order', '>', $current->sort_order)->decrement('sort_order', 1);

        return redirect()->back()->with('info', trans('admin.delete_successful'));
    }

    public function setLocale(Request $request)
    {
        $id = $request->input('id', false);
        $page_id = $request->input('page_id', false);
        $locale = $request->input('locale', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('PageContentEditor->setLocale: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        if (!$page_id || !is_numeric($page_id))
        {
            Log::critical('PageContentEditor->setLocale: page_id is bad, page_id: ' . $page_id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        if (!$locale || !(Config::get('locales.' . $locale) || $locale == 'all'))
        {
            Log::critical('PageContentEditor->setLocale: locale is bad, locale: ' . $locale);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = PageContents::findOrFail($id);

        $current->locale = $locale;
        $current->save();

        return redirect()->back()->with('info', trans('admin.success'));
    }
}
