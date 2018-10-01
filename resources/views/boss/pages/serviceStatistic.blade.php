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
								<select class="custom-select" id="service" name="service_id">
									@foreach($services as $service)
										<option value="{{$service->id}}"
										        @if(isset($service_id) and $service->id == $service_id)selected @endif>
											{{$service->name}}
										</option>
									@endforeach
								</select>
								<select class="custom-select ml-2 mr-2" id="interval" name="interval">
									<option value="today" @if($interval=='today')selected @endif>Сегодня</option>
									<option value="yesterday" @if($interval=='yesterday')selected @endif>Вчера</option>
									<option value="start_of_month" @if($interval=='start_of_month')selected @endif>С
										начала текущего месяца
									</option>
									<option value="month" @if($interval=='month')selected @endif>За прошлый месяц
									</option>
								</select>
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