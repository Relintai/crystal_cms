	{!! link_to('admin/admin_panel', trans('admin.back')) !!}<br>

	{{ trans('admin.artisan') }}<br>
	
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
	
    {!! Form::open(array('url' => 'admin/artisan')) !!}
    {{ trans('admin.command') }}:<br>
    {!! Form::text('command') !!}<br>
	{!! Form::submit(trans('admin.run')) !!}
	
    {!! Form::close() !!}
    <br><br>