@extends('boss.layout')
@section('main_content')
	<div class="container">
		<form action="{{route('admins.bindNiks')}}" method="POST"> {{csrf_field()}}
			<div class="row">
				<div class="col-md-12 mb-md-2 mb-lg-3">
					<div class="card">
						<h5 class="card-header">Связать администратора и его ники</h5>
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-lg-5">
									<div class="input-group">
										<div class="input-group-prepend">
											<label class="input-group-text" for="admins">Администраторы</label>
										</div>
										<select class="custom-select" id="admins" name="user_id">
											@foreach($admins as $admin)
												<option value="{{$admin->id}}">{{$admin->name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-lg-2 text-center">
									<button type="submit" class="btn btn-success"><i class="fas fa-link"></i></button>
									<button type="reset" class="btn btn-dark"><i class="fas fa-eraser"></i></button>
								</div>
								<div class="col-lg">
									<div class="form-group">
										<label for="adminsNiksSelect">Ники администраторов</label>
										<select name="adminNikIds[]" multiple class="form-control" id="adminsNiksSelect"
										        size="5">
											@foreach($adminNiks as $adminNik)
												<option value="{{$adminNik->id}}">
													{{$adminNik->admin_nik}} ({{$adminNik->getService->name}})
												</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Ники администраторов</h5>
					<div class="card-body">
						<table class="table table-striped">
							<thead>
							<tr>
								<th>ID ник / реальн</th>
								<th>Клиент</th>
								<th>Ник администратора</th>
								<th>Реальный</th>
								<th>Сложность</th>
							</tr>
							</thead>
							<tbody>
							@foreach($adminNiks as $adminNik)
								<tr>
									<td>{{$adminNik->id}}/{{$adminNik->user_id}}</td>
									<td>{{$adminNik->getService['name']}}</td>
									<td>{{$adminNik->admin_nik}}</td>
									<td>{{$adminNik->getAdmin['name']}}</td>
									<td>{{$adminNik->getService['compl']}}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Админисистраторы и их Ники у Клиентов</h5>
					<div class="card-body">
						<table class="table table-striped">
							<thead>
							<tr>
								<th>#</th>
								<th>Администратор</th>
								<th>Его ник(-и)</th>
								<th>Клиент(-ы)</th>
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
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection