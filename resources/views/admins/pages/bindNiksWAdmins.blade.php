@extends('admin.layout')
@section('main_content')
	<form action="{{route('realAdmin.bindNiks')}}" method="POST"> {{csrf_field()}}
		<p>
			<select name="admin_id" id="admin">
				@foreach($admins as $admin)
					<option value="{{$admin->id}}">{{$admin->name}}</option>
				@endforeach
			</select>
			<input type="submit" value="Bind!">
			<input type="reset" value="Reset">
			<select name="adminNikIds[]" id="adminNiks" multiple size="5">
				@foreach($adminNiks as $adminNik)
					<option value="{{$adminNik->admin_nik_id}}">
						{{$adminNik->admin_nik}} ({{$adminNik->getService->name}})
					</option>
				@endforeach
			</select>
		</p>
	</form>
	<p><a href="{{route('index')}}">Go Home</a>
	<a href="{{route('realAdmin.create')}}">Create new real admin</a></p>
	@include('admin.parts.msg')
	<h3>Admin niks and services</h3>
	<table border="1">
		<thead>
		<tr>
			<th># nik / real</th>
			<th>Service</th>
			<th>Admin nik</th>
			<th>real name</th>
			<th>Complicate</th>
		</tr>
		</thead>
		<tbody>
		@foreach($adminNiks as $adminNik)
			<tr>
				<td>{{$adminNik->admin_nik_id}}/{{$adminNik->getRealAdmin['id']}}</td>
				<td>{{$adminNik->getService['name']}}</td>
				<td>{{$adminNik->admin_nik}}</td>
				<td>{{$adminNik->getAdmin['name']}}</td>
				<td>{{$adminNik->getService['compl']}}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<h3>Admin and service and own nicks</h3>
	<table border="1">
		<thead>
		<tr>
			<th>#</th>
			<th>real admin</th>
			<th>nik</th>
			<th>service</th>
		</tr>
		</thead>
		<tbody>
		@foreach($adminNiksVV as $admin)
			<tr>
				<td>{{$admin->id}}</td>
				<td>{{$admin->name}}</td>
				<td>@foreach($admin->getNiks as $nik){{$nik->admin_nik}}<br>@endforeach</td>
				<td>@foreach($admin->getServices as $service){{$service->name}}
					<small>({{$service->compl}})</small><br>@endforeach</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endsection