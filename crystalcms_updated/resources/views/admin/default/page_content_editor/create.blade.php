	{!! link_to('admin/menu_editor', trans('admin.back')) !!}<br>

	<h1>{{ trans('admin.page_content_editor') }}</h1>
	
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

	<div class="info">
		{{ trans('admin.create_edit_help') }}
	</div>
	
    {!! Form::model($menuentry, array('url' => 'admin/menu_editor')) !!}
    {!! Form::hidden('id', $id) !!}<br>
    {{ trans('admin.name:key') }}:<br>
    {!! Form::text('name_key') !!}<br>
    URL:<br>
    {!! Form::text('url') !!}<br><br>

	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>