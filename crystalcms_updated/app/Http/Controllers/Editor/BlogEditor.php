<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

use App\Models\Blogs;
use App\Models\BlogEntries;

class BlogEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($blog_id, Request $request)
    {
        $info = $request->session()->get('info', false);

        $blog = Blogs::findOrFail($blog_id);

        return Theme::AdminView('blog_editor/blog_entry_add_edit', [
                        'blog' => $blog,
                        'info' => $info,
                        'blog_id' => $blog_id,
                        'entry' => null,
                        'entry_id' => null]);
    }

    public function edit($blog_id, $entry_id, Request $request)
    {
        $info = $request->session()->get('info', false);

        $blog = Blogs::findOrFail($blog_id);

        $entry = BlogEntries::findOrFail($entry_id);

        return Theme::AdminView('blog_editor/blog_entry_add_edit', [
                        'blog' => $blog,
                        'info' => $info,
                        'blog_id' => $blog_id,
                        'entry' => $entry,
                        'entry_id' => $entry_id]);
    }

    public function delete($blog_id, $entry_id, Request $request)
    {
        $info = $request->session()->get('info', false);

        $blog = Blogs::findOrFail($blog_id);

        $entry = BlogEntries::findOrFail($entry_id);

        $entry->delete();

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $entry_id = $request->input('entry_id');

        /*
        TODO fix this!
        if ($entry_id)
        {
            if (!$this->permissions->get('edit'))
            {
                abort(404);
            }
        }
        else
        {
            if (!$this->permissions->get('add'))
            {
                abort(404);
            }
        }
        */

        $nomod_text = $request->input('nomod_text');
        $blog_id = $request->input('blog_id');

        $blog = Blogs::findOrFail($blog_id);

        $entry = null;
        if ($entry_id)
        {
            $entry = BlogEntries::findOrFail($entry_id);
        }
        else
        {
            $entry = new BlogEntries();
        }

        $parser = new \JBBCode\Parser();
        $parser->addCodeDefinitionSet(new \App\Helpers\BBCodeDefinitionSet());

        $entry->title = $request->input('title');
        $entry->blog_id = $blog->id;
        $entry->nomod_text = $nomod_text;

        $t = $this->textToHTML($nomod_text);
        $parser->parse($t);
        $t = $parser->getAsHtml();

        $entry->text = $t;
        $entry->truncated_text = $t;
        
        $entry->save();

        return redirect($blog->url);
    }

    protected function textToHTML($text)
    {
        $escaped = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5);

        $bradded = str_replace("\n", "<br>", $escaped);

        return $bradded;
    }
}
