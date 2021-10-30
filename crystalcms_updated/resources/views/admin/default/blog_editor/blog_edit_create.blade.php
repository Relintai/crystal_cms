	{!! link_to('admin/blog_editor', trans('admin.back')) !!}<br>

	<h1>{{ trans('admin.blog_editor') }}</h1>
	
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

	@if ($info)
	<div class="info">
		{{ $info }}
	</div>
	@endif
	
    {!! Form::model($blog, array('url' => 'admin/blog_editor/blog_edit_create')) !!}
    {!! Form::hidden('id', $id) !!}<br>
    {{ trans('admin.name') }}:<br>
    {!! Form::text('name') !!}<br>
    URL:<br>
    {!! Form::text('url') !!}<br>

	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>