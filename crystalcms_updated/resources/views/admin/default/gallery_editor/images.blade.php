	{!! link_to('/', trans('admin.back')) !!}<br>

	{{ trans('admin.gallery_editor') }}<br>
	
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

	<ul>
	@if (count($gallery_data))
	@for ($i = 0; $i < count($gallery_data); $i++)
		<li>
			<div>
			<a href="">
				<img class="galleryimage" src="{!! asset('img/gallery/' . $content_gallery_data->folder . '/thumb/' . $gallery_data[$i]->image_thumb) !!}" alt="{{ $gallery_data[$i]->description }}">
			</a>
			</div>
			<div>
				- {{ $gallery_data[$i]->description }}
			</div>
			<div>
				<ul>
					<li>
						<a href="{!! url('editor/gallery_editor/images/edit/' . $page_id . '/' . $gallery_id . '/' . $gallery_data[$i]->id) !!}">
							{{ trans('admin.edit') }}
						</a>
					</li>
					<li>
						@if ($i != 0)
							{!! Form::open(array('url' => 'editor/gallery_editor/images/up')) !!}
							{!! Form::hidden('page_id', $page_id) !!}
							{!! Form::hidden('gallery_id', $gallery_id) !!}
							{!! Form::hidden('gallery_data_id', $gallery_data[$i]->id) !!}
							{!! Form::submit('<') !!}
							{!! Form::close() !!}
						@else
							<
						@endif
					</li>
					<li>
						@if ($i + 1 != count($gallery_data))
							{!! Form::open(array('url' => 'editor/gallery_editor/images/down')) !!}
							{!! Form::hidden('page_id', $page_id) !!}
							{!! Form::hidden('gallery_id', $gallery_id) !!}
							{!! Form::hidden('gallery_data_id', $gallery_data[$i]->id) !!}
							{!! Form::submit('>') !!}
							{!! Form::close() !!}
						@else
							>
						@endif
					</li>
					<li>
						{!! Form::open(array('url' => 'editor/gallery_editor/images/delete')) !!}
						{!! Form::hidden('page_id', $page_id) !!}
						{!! Form::hidden('gallery_id', $gallery_id) !!}
						{!! Form::hidden('gallery_data_id', $gallery_data[$i]->id) !!}
						{!! Form::submit('x') !!}
						{!! Form::close() !!}
					</li>
				</ul>
			</div>
		</li>
	@endfor
	@endif
	</ul>