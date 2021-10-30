@if ($permissions->get('edit'))
<div>
{{ trans('contentcontroller.text') }} {!! link_to('editor/text_editor/' . $dbrow->page_id . '/' .  $textdata->id ,trans('contentcontroller.edit')) !!}
</div>
@endif
<div>
@if ($textdata)
	{!! $textdata->text !!}
@endif
</div>