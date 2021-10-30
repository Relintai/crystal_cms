	<h1>{{ trans('admin.page_content_editor') }}</h1>

	{!! link_to('admin/page_content_editor', trans('admin.back')) !!}<br>

	<style>
	li {
		display: inline-block;
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

	@if (count($page_contents) > 0)
		@for ($i = 0; $i < count($page_contents); $i++)
				<div class="row">
					<ul>
						<li>
							{{ trans('admin.type') }}: [{{ trans('contentcontroller.' . $page_contents[$i]->type) }}]
						</li>
						<li>
							@if ($i != 0)
								{!! Form::open(array('url' => 'admin/page_content_editor/up')) !!}
								{!! Form::hidden('id', $page_contents[$i]->id) !!}
								{!! Form::submit(trans('admin.up')) !!}
								{!! Form::close() !!}
							@else
								{{ trans('admin.up') }}
							@endif
						</li>
						<li>
							@if ($i + 1 != count($page_contents))
								{!! Form::open(array('url' => 'admin/page_content_editor/down')) !!}
								{!! Form::hidden('id', $page_contents[$i]->id) !!}
								{!! Form::submit(trans('admin.down')) !!}
								{!! Form::close() !!}
							@else
								{{ trans('admin.down') }}
							@endif
						</li>
						<li>
							{!! Form::open(array('url' => 'admin/page_content_editor/delete')) !!}
							{!! Form::hidden('id', $page_contents[$i]->id) !!}
							{!! Form::hidden('page_id', $page_id) !!}
							{!! Form::submit(trans('admin.delete')) !!}
							{!! Form::close() !!}
						</li>
						<li>
							{!! Form::open(array('url' => 'admin/page_content_editor/setlocale')) !!}
							{!! Form::hidden('id', $page_contents[$i]->id) !!}
							{!! Form::hidden('page_id', $page_id) !!}
							{!! Form::select('locale', $locales, $page_contents[$i]->locale) !!}
							{!! Form::submit(trans('admin.set')) !!}
							{!! Form::close() !!}
						</li>
					</ul>
				</div>
				<div class="settingsrow">
					{!! $contents[$i]->getSettingsHTML() !!}
				</div>
		@endfor
	@endif

	{!! link_to('admin/page_content_editor/add/' . $page_id, trans('admin.add')) !!}