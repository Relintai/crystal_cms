@if ($permissions->get('edit'))
<div>
{{ trans('contentcontroller.image') }} {!! link_to('editor/image_editor/' . $dbrow->page_id . '/' .  $imagedata->id ,trans('contentcontroller.edit')) !!}
</div>
@endif
<div>
@if ($imagedata)
	<img class="contentimage" src="{!! asset('img/images/small/' . $imagedata->image_small) !!}" alt="{{ $imagedata->alt }}">
@endif
</div>