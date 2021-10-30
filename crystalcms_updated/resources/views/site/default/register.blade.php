
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif
	
	Register:<br>
	{!! Form::open(array('url' => 'register')) !!}
	Username: {!! Form::text('username') !!}<br>
	Email: {!! Form::text('email') !!}<br>
	Password: {!! Form::password('password') !!}<br>
	Password again: {!! Form::password('password2') !!}<br>
	I accept the EULA {!! Form::checkbox('eula') !!}<br>
	{!! Form::submit('Register!') !!}<br>
	{!! Form::close() !!}