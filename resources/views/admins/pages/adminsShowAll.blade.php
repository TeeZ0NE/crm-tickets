@extends('admins.layout')
@section('main_content')
	<h3>Admins</h3>
	<p>
		<a href="{{route('index')}}">Go Home</a>
		|<a href="{{route('admins.nicks')}}">Bind Admin Nik with real admin</a>
	</p>
	<h4>Add real admin</h4>
	<p><a href="{{route('admins.create')}}">Create new real admin</a></p>
	<table>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>operations</th>
		</tr>
		<tbody>@php $i=0;@endphp
		@foreach($admins as $admin)
			<tr>
				<td>{{++$i}}</td>
				<td>{{$admin->name}}</td>
				<td>
					<form action="{{route('admins.destroy',$admin->id)}}" method="post">
						@method('DELETE')
						@csrf
						<button type="submit" onclick="return confirm('Ви впевнені?')">Del</button>
						<button data-name="{{$admin->name}}" class="rename-admin">Rename</button>
					</form>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endsection