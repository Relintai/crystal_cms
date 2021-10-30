	<style>
	li {
		display: inline-block;
	}
	</style>

	{{ trans('admin.blog_editor') }}<br>

	{!! link_to('admin/admin_panel', trans('admin.back')) !!}<br>

	@if ($info)
	<div class="info">
		{{ $info }}
	</div>
	@endif

	@if (count($blogs) > 0)
		@for ($i = 0; $i < count($blogs); $i++)
				<div class="row">
					<ul>
						<li>
							<a href="{!! action('Admin\BlogEditor@show', ['id' => $blogs[$i]->id]) !!}">
								Id: {{ $blogs[$i]->id }}, {{ trans('admin.name') }}: {{ $blogs[$i]->name }}, URL: {{ $blogs[$i]->url }}  
							</a>
						</li>
						<li>
							{!! Form::open(array('url' => 'admin/blog_editor/delete')) !!}
							{!! Form::hidden('id', $blogs[$i]->id) !!}
							{!! Form::submit(trans('admin.delete') . " NYI") !!}
							{!! Form::close() !!}
						</li>
					</ul>
				</div>
		@endfor
	@endif

	{!! link_to('admin/blog_editor/create', trans('admin.new_blog')) !!}