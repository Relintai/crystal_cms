<!DOCTYPE html>
<html>
<head>
	<title>
		Title
	</title>
	<link rel="stylesheet" type="text/css" href="{!! asset('css/base.css') !!}">
	{!! $head !!}
</head>
<body>
	<div class="menu">
		<ul class="menu">
			@if (count($menu) > 0)
			@foreach ($menu as $e)
			<li class="menuentry">
				{!! link_to($e->url, trans('menu.' . $e->name_key)) !!}
			</li>
			@endforeach
			@endif
		</ul>
	</div>
	<div class="content">
		{!! $content !!}
	</div>
	<footer>
		@if ($rank_settings->get('showadminpanellink'))
			{!! link_to("admin/admin_panel", "Admin Panel") !!}
		@endif
		
		@if ($userdata)
			{!! link_to("logout", "Logout") !!}
		@endif

		{!! link_to('language/en', 'en') !!} {!! link_to('language/hu', 'hu') !!}
	</footer>
</body>
</html>