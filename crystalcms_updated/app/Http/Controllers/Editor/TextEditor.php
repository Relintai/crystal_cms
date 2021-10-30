<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\ContentDataText;
use App\Models\Pages;

class TextEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($page_id, $text_id, Request $request)
    {
        $info = $request->session()->get('info', false);

        $text = ContentDataText::findOrFail($text_id);

        return Theme::AdminView('text_editor/editor', [
                        'text' => $text,
                        'info' => $info,
                        'page_id' => $page_id,
                        'text_id' => $text_id]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //TODO check if user has RBAC_Permission to really edit this text

        $nomod_text = $request->input('nomod_text');
        $page_id = $request->input('page_id');
        $text_id = $request->input('text_id');

        $text = ContentDataText::findOrFail($text_id);
        $page = Pages::findOrFail($page_id);

        $parser = new \JBBCode\Parser();
        $parser->addCodeDefinitionSet(new \App\Helpers\BBCodeDefinitionSet());

        $text->nomod_text = $nomod_text;

        $t = $this->textToHTML($nomod_text);
        $parser->parse($t);
        $t = $parser->getAsHtml();

        $text->text = $t;
        $text->save();

        return redirect($page->url);
    }

    protected function textToHTML($text)
    {
        $escaped = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5);

        $bradded = str_replace("\n", "<br>", $escaped);

        return $bradded;
    }
}
