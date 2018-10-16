@extends('boss.layout')
@section('title','Статистика')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-5">
				@include('parts.rates')
			</div>
		<div class="col-md-8 mx-auto">
			@include('boss.parts.rateMonthForm')
		</div>
		</div>
	</div>
@endsection
@push('js-scripts')
	<script type="text/javascript" src="{{asset('js/slide-statistic.min.js')}}"></script>
@endpush