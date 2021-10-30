	{!! link_to('/', trans('admin.back')) !!}<br>

	{{ trans('admin.image_editor') }}<br>
	
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
	
    {!! Form::model($image_data, array('url' => 'editor/image_editor', 'files' => true)) !!}
    {!! Form::hidden('page_id', $page_id) !!}<br>
    {!! Form::hidden('image_id', $image_id) !!}<br>
    {{ trans('admin.alt_text') }}:<br>
    {!! Form::text('alt') !!}<br>
    {{ trans('admin.image_only_owerwritten_if_present') }}:<br>
	{!! Form::file('image', ['accept' => "image/*"]) !!}<br><br>
	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>