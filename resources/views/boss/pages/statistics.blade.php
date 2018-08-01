@extends('boss.layout')
@section('title','Статистика')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				@include('parts.rates')
			</div>
		</div>
	</div>
@endsection