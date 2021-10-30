
	@if (count($errors) > 0)
	<div class="errors">
			@foreach ($errors->all() as $e)
				{{ $e }}<br>
			@endforeach
	</div>
	@endif

Login:
	{!! Form::open(array('url' => 'login')) !!}
	Username: {!! Form::text('username') !!}<br>
	Password: {!! Form::password('password') !!}<br>
	{!! Form::submit('Login!') !!}
	{!! Form::close() !!} <br>
	{!! link_to('register', 'Register') !!}