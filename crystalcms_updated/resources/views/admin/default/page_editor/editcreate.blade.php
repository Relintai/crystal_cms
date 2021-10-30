	{!! link_to('admin/page_editor', trans('admin.back')) !!}<br>

	{{ trans('admin.page_editor') }}<br>
	
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

	<div class="info">
		{{ trans('admin.page_editor_help') }}
	</div>
	
    {!! Form::model($page, array('url' => 'admin/page_editor')) !!}
    {!! Form::hidden('id', $id) !!}<br>
    {{ trans('admin.name_key') }}:<br>
    {!! Form::text('name') !!}<br>
    URL:<br>
    {!! Form::text('url') !!}<br><br>

	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>