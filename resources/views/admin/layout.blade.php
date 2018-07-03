<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<style type="text/css"
	       href="{{ mix('css/app.css') }}"></style>{{-- styles --}}
<body>
@section('main_content')@show
	<div>@include('admin.parts.footer')</div>
</div>
</body>
</html>