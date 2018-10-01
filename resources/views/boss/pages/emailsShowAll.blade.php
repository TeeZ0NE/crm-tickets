@extends('boss.layout')
@section('title','e-mails')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Почтовые ящики</h5>
					<div class="card-body">
						<table class="table table-striped">
							<tr class="text-center">
								<th>ID</th>
								<th>E-mail</th>
								<th></th>
							</tr>
							<tbody>
							@foreach($emails as $email)
								<tr>
									<td class="align-middle">{{$email->id}}</td>
									<td>
										<form action="{{route('emails.update',$email->id)}}" method="post">
											@method('PUT')
											@csrf
											<div class="input-group">
												<input type="email" aria-label="e-mail" class="form-control"
												       value="{{$email->email}}" name="email">
												<button type="submit" class="store-email btn btn-info">
													<i class="fas fa-save"></i>
												</button>
											</div>
										</form>
									</td>
									<td>
										<form action="{{route('emails.destroy',$email->id)}}" method="post">
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