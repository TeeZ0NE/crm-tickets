@extends('boss.layout')
@section('title','Все админы')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Админисистраторы</h5>
					<div class="card-body">
						<table class="table table-striped">
							<tr class="text-center">
								<th>#</th>
								<th>ID</th>
								<th>Имя</th>
								<th>Активен</th>
								<th class="text-right">Операции</th>
							</tr>
							<tbody>@php $i=0;@endphp
							@foreach($admins as $admin)
								<tr>
									<td>{{++$i}}</td>
									<td>{{$admin->id}}</td>
									<td>{{$admin->name}}</td>
									<td class="text-center">
										@if($admin->active)
											<form action="{{route('admin.deactivate',$admin->id)}}" method="post">
												<input type="checkbox" name="deactivate" class="submit" checked
												       title="deactivate">
												@csrf
												@method('PUT')
											</form>
										@else
											<i class="fas fa-minus"></i>
										@endif
									</td>
									<td class="text-right">
										<form action="{{route('admins.destroy',$admin->id)}}" method="post">
											@method('DELETE')
											@csrf
											<button type="submit" class="btn btn-dark"
											        onclick="return confirm('Ви впевнені?')"><i
														class="fas fa-eraser"></i>
											</button>
											<button data-name="{{$admin->name}}" class="rename-admin btn btn-info">
												<i class="fas fa-pencil-alt"></i>
											</button>
										</form>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	@push('js-scripts')
		<script type="text/javascript" src="{{asset('js/submitOnCheckbox.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/adminRename.min.js')}}"></script>
	@endpush
@endsection