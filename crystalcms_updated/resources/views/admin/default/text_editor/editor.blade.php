	{!! link_to('/', trans('admin.back')) !!}<br>

	{{ trans('admin.text_editor') }}<br>
	
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

	@if ($info)
	<div class="info">
		{{ trans('admin.page_editor_help') }}
	</div>
	@endif

	<div>
		{!! link_to('editor/image_uploader', trans('admin.image_uploader')) !!}
	</div>
	
    {!! Form::model($text, array('url' => 'editor/text_editor')) !!}
    {!! Form::hidden('page_id', $page_id) !!}<br>
    {!! Form::hidden('text_id', $text_id) !!}<br>
    {{ trans('admin.text') }}:<br>
    {!! Form::textarea('nomod_text') !!}<br>

	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>