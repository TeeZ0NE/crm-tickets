@extends('admins.layout')
@section('title','Статистика')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				@foreach($statistic4AllAdminsByMonths as $statistic4AllAdminsByMonth)
					@include('boss.parts.ratesByMonth',[
					'month'=>$Carbon->now()->startOfMonth()->subMonth($iterator)->toDateString(),
					'endMonth' => $Carbon->now()->endOfMonth()->subMonth($iterator)->toDateString(),
					'statistic4AllAdmins'=>$statistic4AllAdminsByMonth
					])
					@php $iterator--;@endphp
				@endforeach
			</div>
		</div>
	</div>
@endsection
@push('js-scripts')
	<script type="text/javascript" src="{{asset('js/slide-statistic.min.js')}}"></script>
@endpush