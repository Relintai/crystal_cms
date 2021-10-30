	{!! link_to('admin/rbac_editor', trans('admin.back')) !!}<br>

	<h1>{{ trans('admin.rbac_editor') }}</h1>
	
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

    {!! Form::model($rankentry, array('url' => 'admin/rbac_editor/rank_editor')) !!}
    {!! Form::hidden('id', $id) !!}<br>
    {{ trans('admin.name') }}:<br>
    {!! Form::text('name') !!}<br>
    {{ trans('admin.name_internal') }}:<br>
    {!! Form::text('name_internal') !!}<br><br>

	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>