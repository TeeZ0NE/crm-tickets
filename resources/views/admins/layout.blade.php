<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="refresh" content="120">
	<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
	<link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
@includeWhen(Session::has('msg'),'parts.msg')
@includeWhen($errors->any(),'parts.errors')
<div id="app">
	@if(Auth::check())@include('admins.parts.nav')@endif
	<main class="py-4">
			@yield('main_content')
	</main>
</div>
<div>@include('admins.parts.footer')</div>
</body>
</html>