	<h1>{{ trans('admin.rbac_editor') }}</h1>

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

	@if (count($ranks) > 0)
		@for ($i = 0; $i < count($ranks); $i++)
			
				<div class="row">
					<a href="{!! url('admin/rbac_editor/show', [$ranks[$i]->id]) !!}">
						Id: {{ $ranks[$i]->id }}, {{ trans('admin.name') }}: {{ $ranks[$i]->name }}, {{ trans('admin.name_internal') }}: {{ $ranks[$i]->name_internal }}   
					</a>
					<a href="{!! url('admin/rbac_editor/rank_editor', [$ranks[$i]->id]) !!}">
						{{ trans('admin.edit_names') }}
					</a>
				</div>
			
		@endfor
	@endif

	{!! link_to('admin/rbac_editor/rank_editor', trans('admin.new_rank')) !!}