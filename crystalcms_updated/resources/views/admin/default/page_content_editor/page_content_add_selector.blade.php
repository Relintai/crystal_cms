	<h1>{{ trans('admin.page_content_editor') }}</h1>

	{!! link_to('admin/page_content_editor/show/' . $page_id, trans('admin.back')) !!}<br>

	<style>
	li {
		display: inline-block;
	}
	</style>

	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

	@foreach(CC::getContentData() as $types)
			<div class="row">
				<ul>
					<li>
						{{ trans($types['name']) }}: {{ trans($types['description']) }}
					</li>
					<li>
						{!! Form::open(array('url' => 'admin/page_content_editor/add')) !!}
						{!! Form::hidden('page_id', $page_id) !!}
						{!! Form::hidden('contentcontroller', $types['contentcontroller']) !!}
						{!! Form::submit(trans('admin.add') . ' ' . trans($types['name'])) !!}
						{!! Form::close() !!}
					</li>
				</ul>
			</div>
		
	@endforeach