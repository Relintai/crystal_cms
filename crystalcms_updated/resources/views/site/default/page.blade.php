
	@if ($info)
	<div class="info">
		{{ $info }}
	</div>
	@endif
	@if (count($contents) > 0)
	@foreach ($contents as $c)
	<div class="maincontainer">
		{!! $c->getHTML() !!}
	</div>
	@endforeach
	@endif
