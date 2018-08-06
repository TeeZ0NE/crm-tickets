@extends('boss.layout')
@section('title','Статистика')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				@foreach($statistic4AllAdminsByMonths as $statistic4AllAdminsByMonth)
				@include('boss.parts.ratesByMonth',[
				'month'=>$Carbon->now()->subMonth($iterator)->startOfMonth()->toDateString(),
				'endMonth' => $Carbon->now()->subMonth($iterator)->endOfMonth()->toDateString(),
				'statistic4AllAdmins'=>$statistic4AllAdminsByMonth
				])
					@php $iterator--;
					@endphp
					@if(!$curr_month && !$iterator) @break @endif
					@endforeach
			</div>
		</div>
		<div class="col-md-8 mx-auto">
			@include('boss.parts.rateMonthForm',['curr_month'=>$curr_month])
		</div>
	</div>
@endsection