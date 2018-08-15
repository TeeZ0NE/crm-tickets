<!DOCTYPE html>
<html lang="ru" class="position-relative h-100">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="refresh" content="120">
	<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
	<link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="position-relative">
@includeWhen(Session::has('msg'),'parts.msg')
@includeWhen($errors->any(),'parts.errors')
<div id="app" class="h-100 position-relative mb-5 pb-5">
	@if(Auth::guard('boss')->check())@include('boss.parts.nav')@endif
	<main class="py-4">
			@yield('main_content')
	</main>
</div>
<footer class="position-absolute w-100 text-white-50 bg-dark mt-lg-3 p-lg-5 text-center">@include('boss.parts.footer')</footer>
</body>
</html>