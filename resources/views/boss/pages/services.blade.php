@extends('boss.layout')
@section('title','Клиенты')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Клиенты (тикетницы)</h5>
					<div class="card-body">
						<table class="table table-striped">
							<tr class="text-center">
								<th>ID</th>
								<th>Имя</th>
								<th></th>
							</tr>
							<tbody>
							@foreach($services as $service)

								<tr>
									<td class="align-middle">{{$service->id}}</td>
									<td>
										<form action="{{route('services.update',$service->id)}}" method="post">
											@method('PUT')
											@csrf
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">Клиент, сложность</span>
												</div>
												<input type="text" aria-label="name" class="form-control"
												       value="{{$service->name}}" name="name">
												<input type="number" aria-label="compl" class="form-control"
												       value="{{$service->compl}}" step=".1" name="compl">
												<button type="submit"  class="store-service btn btn-info">
													<i class="fas fa-save"></i>
												</button>
											</div>
										</form>
									</td>
									<td>
										<form action="{{route('services.destroy',$service->id)}}" method="post">
											@method('DELETE')
											@csrf
											<button type="submit" class="btn btn-dark"
											        onclick="return confirm('Ви впевнені?')"><i
														class="fas fa-eraser"></i>
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
@endsection