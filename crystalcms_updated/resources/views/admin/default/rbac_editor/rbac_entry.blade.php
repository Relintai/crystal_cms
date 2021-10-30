	<h1>{{ trans('admin.rbac_editor') }}</h1>

	{!! link_to('admin/rbac_editor', trans('admin.back')) !!}<br>

	<style>
	li {
		display: inline-block;
	}

	.rbac_sttings ul {
		padding-left: 0px;
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

	@if (count($data) > 0)
		@for ($i = 0; $i < count($data); $i++)
				<div class="row">
					<ul>
						<li>
							<a href="{!! url('admin/rbac_editor/group_editor', [$rank_id, $data[$i]->id]) !!}">
							{{ trans('admin.name') }}: {{ $data[$i]->name }}, URL: {{ $data[$i]->url }}, {{ trans('admin.revoke') }}: {{ $data[$i]->revoke }}
							</a>
						</li>
						<li>
							@if ($i != 0)
								{!! Form::open(array('url' => 'admin/rbac_editor/up')) !!}
								{!! Form::hidden('id', $data[$i]->id) !!}
								{!! Form::submit(trans('admin.up')) !!}
								{!! Form::close() !!}
							@else
								{{ trans('admin.up') }}
							@endif
						</li>
						<li>
							@if ($i + 1 != count($data))
								{!! Form::open(array('url' => 'admin/rbac_editor/down')) !!}
								{!! Form::hidden('id', $data[$i]->id) !!}
								{!! Form::submit(trans('admin.down')) !!}
								{!! Form::close() !!}
							@else
								{{ trans('admin.down') }}
							@endif
						</li>
						<li>
							{!! Form::open(array('url' => 'admin/rbac_editor/delete')) !!}
							{!! Form::hidden('id', $data[$i]->id) !!}
							{!! Form::submit(trans('admin.delete')) !!}
							{!! Form::close() !!}
						</li>
					</ul>
					{!! Form::open(array('url' => 'admin/rbac_editor/update_permissions')) !!}
					{!! Form::hidden('id', $data[$i]->permission_id) !!}
					<ul>
						<?php
							$exploded = explode(',', $data[$i]->permissions);
						?>
						@foreach (App\Models\RBACPermissions::$permissions as $p)
						<li>{!! Form::checkbox($p, 'true', App\Models\RBACPermissions::rbac_array_contains($exploded, $p)) !!} {{ trans('rbac_permissions.' . $p) }}</li>
						@endforeach
						<li>{!! Form::submit('Save') !!}</li>
					</ul>
					{!! Form::close() !!}
				</div>
		@endfor
	@endif

	<div class="rbac_sttings">
		{!! Form::open(array('url' => 'admin/rbac_editor/update_rank_settings')) !!}
		{!! Form::hidden('rank_id', $rank->id) !!}
		<ul>
			<?php
				$exploded = explode(',', $rank->settings);
			?>
			@foreach (App\Models\RBACRanks::$settings as $p)
			<li>{!! Form::checkbox($p, 'true', App\Models\RBACRanks::rbac_array_contains($exploded, $p)) !!} {{ trans('rbac_settings.' . $p) }}</li>
			@endforeach
		</ul>

		{!! Form::submit(trans('save')) !!}
		{!! Form::close() !!}

	</div>

	{!! link_to('admin/rbac_editor/group_editor/' . $rank_id, trans('admin.new_group')) !!}