@extends('admin.layout')
@section('main_content')
<h3>Admins</h3>

<h4>Add real admin</h4>
<p><a href="{{route('realAdmin.create')}}">Create new real admin</a></p>

@endsection