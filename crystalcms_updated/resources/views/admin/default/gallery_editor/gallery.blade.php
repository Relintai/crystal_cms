	{!! link_to('/', trans('admin.back')) !!}<br>

	{{ trans('admin.gallery_editor') }}<br>
	
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
	
    {!! Form::model($gallery_data, array('url' => 'editor/gallery_editor')) !!}
    {!! Form::hidden('page_id', $page_id) !!}<br>
    {!! Form::hidden('gallery_id', $gallery_id) !!}<br>
    {{ trans('admin.name') }}:<br>
    {!! Form::text('name') !!}<br>
    {{ trans('admin.folder') }}:<br>
    {!! Form::text('folder') !!}<br>
    {{ trans('admin.description') }}:<br>
    {!! Form::text('description') !!}<br>
	{!! Form::submit('Save') !!}
	
    {!! Form::close() !!}
    <br><br>
