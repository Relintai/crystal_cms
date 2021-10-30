	<h1>{{ trans('admin.page_content_editor') }}</h1>

	{!! link_to('admin/admin_panel', trans('admin.back')) !!}<br>

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

	@if ($info)
	<div class="info">
		{{ $info }}
	</div>
	@endif

	{{ trans('admin.pages') }}:<br>
	@if (count($pages) > 0)
		@for ($i = 0; $i < count($pages); $i++)
			
				<div class="row">
					<a href="{!! url('admin/page_content_editor/show', [$pages[$i]->id]) !!}">
						Id: {{ $pages[$i]->id }}, {{ trans('admin.name') }}: {{ $pages[$i]->name }}, URL: {{ $pages[$i]->url }}  
					</a>
				</div>
			
		@endfor
	@endif