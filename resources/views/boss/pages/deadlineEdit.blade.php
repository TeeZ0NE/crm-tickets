@extends('boss.layout')
@section('title','Добавить deadline')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 mb-md-2 mb-lg-3 mx-auto">
				<div class="card">
					<h5 class="card-header">Временной интервал</h5>
					<div class="card-body">
						<form action="{{route('deadline.update',$id)}}" method="post">
							@csrf
							@method('PUT')
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Часы, Минуты</span>
								</div>
								<input type="number" aria-label="hour" class="form-control"
								       value="{{$hour or 0}}" name="hour" min="0" max="23">
								<input type="number" aria-label="minutes" class="form-control"
								       value="{{$minutes or 0}}" name="minutes" min="0" max="59">
								<button type="submit"  class="store-service btn btn-info">
									<i class="fas fa-save"></i>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection