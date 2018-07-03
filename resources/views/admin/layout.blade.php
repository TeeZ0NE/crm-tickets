<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<style type="text/css"
	       href="{{ mix('css/app.css') }}"></style>{{-- styles --}}
</head>
<body>
<div>@section('main_content')@show</div>
<div>@include('admin.parts.footer')</div>
</body>
</html>