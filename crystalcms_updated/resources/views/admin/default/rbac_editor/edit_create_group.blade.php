	{!! link_to('admin/rbac_editor/show/' . $rank_id, trans('admin.back')) !!}<br>

	<h1>{{ trans('admin.rbac_editor') }}</h1>
	
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

    {!! Form::model($groupentry, array('url' => 'admin/rbac_editor/group_editor/')) !!}
    {!! Form::hidden('rank_id', $rank_id) !!}
    {!! Form::hidden('group_id', $group_id) !!}<br>
    {{ trans('admin.name') }}:<br>
    {!! Form::text('name') !!}<br>
    {{ trans('admin.rbac_url') }}:<br>
    {!! Form::text('url') !!}<br>
    {!! Form::checkbox('revoke', 'true') !!} {{ trans('admin.revoke') }}<br><br>

	{!! Form::submit(trans('admin.save')) !!}
	
    {!! Form::close() !!}
    <br><br>