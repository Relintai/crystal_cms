	{!! link_to('admin/admin_panel', trans('admin.back')) !!}<br>

	{{ trans('admin.theme_editor') }}<br>
	
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
	
    {!! Form::open(array('url' => 'admin/theme_editor')) !!}
    {{ trans('admin.site') }}:<br>
    {!! Form::select('site', $site_themes, $site) !!}<br>
    {{ trans('admin.admin') }}:<br>
	{!! Form::select('admin', $admin_themes, $admin) !!}<br><br>
	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>