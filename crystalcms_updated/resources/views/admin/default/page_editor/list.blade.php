	<style>
	li {
		display: inline-block;
	}
	</style>

	{{ trans('admin.page_editor') }}<br>

	{!! link_to('admin/admin_panel', trans('admin.back')) !!}<br>

	@if ($info)
	<div class="info">
		{{ $info }}
	</div>
	@endif

	@if (count($pages) > 0)
		@for ($i = 0; $i < count($pages); $i++)
				<div class="row">

					<ul>
						<li>
							<a href="{!! action('Admin\PageEditor@show', ['id' => $pages[$i]->id]) !!}">
								Id: {{ $pages[$i]->id }}, {{ trans('admin.name_key') }}: {{ $pages[$i]->name }}, URL: {{ $pages[$i]->url }}  
							</a>
						</li>
						<li>
							{!! Form::open(array('url' => 'admin/page_editor/delete')) !!}
							{!! Form::hidden('id', $pages[$i]->id) !!}
							{!! Form::submit(trans('admin.delete')) !!}
							{!! Form::close() !!}
						</li>
					</ul>
				</div>
		@endfor
	@endif

	{!! link_to('admin/page_editor/create', trans('admin.new_page')) !!}