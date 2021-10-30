	{!! link_to('editor/gallery_editor/images/' . $page_id . '/' . $gallery_id, trans('admin.back')) !!}<br>

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
	
    {!! Form::model($gallery_data, array('url' => 'editor/gallery_editor/add_image', 'files' => true)) !!}
    {!! Form::hidden('page_id', $page_id) !!}
    {!! Form::hidden('gallery_id', $gallery_id) !!}
   	@if ($gallery_data)
    {!! Form::hidden('gallery_data_id', $gallery_data->id) !!}
    @endif
    {{ trans('admin.desciption') }}:<br>
    {!! Form::text('description') !!}<br>
    {{ trans('admin.image_only_owerwritten_if_present') }}:<br>
	{!! Form::file('image', ['accept' => "image/*"]) !!}<br><br>
	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>