@extends('boss.layout')
@section('title','Добавить deadline')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 mb-md-2 mb-lg-3 mx-auto">
				<div class="card">
					<h5 class="card-header">Временные интервалы</h5>
					<div class="card-body">
						<table class="table table-striped">
							<thead>
							<tr>
								<th>ID</th>
								<th>Интервал</th>
								<th>Операции</th>
							</tr>
							</thead>
							<tbody>
							@foreach($deadlines as $deadline)
								<tr class="text-right">
									<td>{{$deadline->id}}</td>
									<td>{{$deadline->deadline}}</td>
									<td>
										<form action="{{route('deadline.destroy',$deadline->id)}}" method="post">
											@method('DELETE')
											@csrf
											<a href="{{route('deadline.edit',$deadline->id)}}" class="btn btn-info"><i class="fas fa-pencil-alt"></i></a>
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