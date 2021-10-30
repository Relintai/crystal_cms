	{!! link_to('/', trans('admin.back')) !!}<br>

	{{ trans('admin.image_uploader') }}<br>
	
	<style>
	li {
		display: inline-block;
	}

	ul {
		padding-left: 2em;
	}

	.settingsrow {
		padding-left: 5em;
	}
	</style>

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

	<div>
		{!! Form::open(array('url' => 'editor/image_uploader', 'files' => true)) !!}
		{{ trans('admin.resize_to_width') }}:<br>
		{!! Form::number('width', '600') !!}<br>
		{!! Form::file('image', ['accept' => "image/*"]) !!}<br>
		{!! Form::submit(trans('admin.upload')) !!}
		{!! Form::close() !!}
	</div>

	<ul>
	@if (count($image_data))
	@for ($i = 0; $i < count($image_data); $i++)
		<li>
			<div>
			<a href="">
				<img class="galleryimage" src="{!! asset('img/uploaded/' . $image_data[$i]->image) !!}">
			</a>
			</div>
			<div>
				<ul>
					<li>
						{{ $image_data[$i]->image }}
					</li>
					<li>
						{!! Form::open(array('url' => 'editor/image_uploader/delete')) !!}
						{!! Form::hidden('image_id', $image_data[$i]->id) !!}
						{!! Form::submit(trans('admin.delete')) !!}
						{!! Form::close() !!}
					</li>
				</ul>
			</div>
		</li>
	@endfor
	@endif
	</ul>