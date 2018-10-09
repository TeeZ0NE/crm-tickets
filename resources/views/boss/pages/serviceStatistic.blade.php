@extends('boss.layout')
@section('title','Статистика по клиенту')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Фильтр</h5>
					<div class="card-body">
						<form action="{{route('services.getStatistic')}}"
						      class="justify-content-center form-inline">
							<div class="form-group">
								@include('boss.parts.servicesList')
								@include('boss.parts.intervalsList')
								<button type="submit" class="btn btn-primary">Получить</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		@isset($statistics)
			<div class="row">
				<div class="col-md-12 mb-md-2 mb-lg-3">
					<div class="card">
						<h5 class="card-header">Отправить email</h5>
						<div class="card-body">
							<form method="post" class="form-inline justify-content-center" action="{{route('services.sendStatistic',['service_id'=>$service_id,'interval'=>$interval])}}">
							@csrf()
								<button class="btn btn-primary">Отправить на e-mail</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 mb-md-2 mb-lg-3">
					<div class="card">
						<h5 class="card-header">Результат</h5>
						<div class="card-body">
							@include('boss.parts.service_statistic_by_interval')
						</div>
					</div>
				</div>
			</div>
		@endisset
	</div>
@endsection