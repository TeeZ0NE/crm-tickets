@extends('admin.layout')
@section('main_content')
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
				<td>{{$adminNik->getRealAdmin['name']}}</td>
				<td>{{$adminNik->getService['compl']}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
	<h3>Admin and service and niks</h3>
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
			<td>@foreach($admin->getServices as $service){{$service->name}}<small>({{$service->compl}})</small><br>@endforeach</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endsection
