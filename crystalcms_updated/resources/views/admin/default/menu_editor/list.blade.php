	<h1>{{ trans('admin.menu_editor') }}</h1>

	{!! link_to('admin/admin_panel', trans('admin.back')) !!}<br>

	<style>
	li {
		display: inline-block;
	}
	</style>

	@if ($info)
	<div class="info">
		{{ $info }}
	</div>
	@endif

	@if (count($menuentries) > 0)
		@for ($i = 0; $i < count($menuentries); $i++)
			
				<div class="row">
					<ul>
						<li>
							<a href="{!! action('Admin\MenuEditor@show', ['id' => $menuentries[$i]->id]) !!}">
							Id: {{ $menuentries[$i]->id }}, {{ trans('admin.name_key') }}: {{ $menuentries[$i]->name_key }} ({{ trans('menu.' . $menuentries[$i]->name_key) }}), URL: {{ $menuentries[$i]->url }}  
							</a>
						</li>
						<li>
							@if ($i != 0)
								{!! Form::open(array('url' => 'admin/menu_editor/up')) !!}
								{!! Form::hidden('id', $menuentries[$i]->id) !!}
								{!! Form::submit(trans('admin.up')) !!}
								{!! Form::close() !!}
							@else
								{{ trans('admin.up') }}
							@endif
						</li>
						<li>
							@if ($i + 1 != count($menuentries))
								{!! Form::open(array('url' => 'admin/menu_editor/down')) !!}
								{!! Form::hidden('id', $menuentries[$i]->id) !!}
								{!! Form::submit(trans('admin.down')) !!}
								{!! Form::close() !!}
							@else
								{{ trans('admin.down') }}
							@endif
						</li>
						<li>
							{!! Form::open(array('url' => 'admin/menu_editor/delete')) !!}
							{!! Form::hidden('id', $menuentries[$i]->id) !!}
							{!! Form::submit(trans('admin.delete')) !!}
							{!! Form::close() !!}
						</li>
					</ul>
				</div>
			
		@endfor
	@endif

	{!! link_to('admin/menu_editor/create', trans('admin.new_entry')) !!}