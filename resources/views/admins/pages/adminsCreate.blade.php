@extends('admins.layout')
@section('main_content')
<form action="{{route('admins.store')}}" method="POST">{{csrf_field()}}
	<input type="text" name="name" placeholder="real admin name" value="{{old('name')}}">
	<p>
		<input type="submit" value="save">
		<a href="{{route('index')}}">Go Back</a>
	</p>
</form>
	@endsection