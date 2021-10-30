	{!! link_to('admin/menu_editor', trans('admin.back')) !!}<br>

	<h1>{{ trans('admin.menu_editor') }}</h1>
	
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
    {{ trans('admin.name_key') }}:<br>
    {!! Form::text('name_key') !!}<br>
    URL:<br>
    {!! Form::text('url') !!}<br>
    Create Page? {!! Form::checkbox('create_page', 'create_page', true) !!}<br><br>

	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>