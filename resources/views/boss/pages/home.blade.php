@extends('boss.layout')
@section('title','Boss Home')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				@include('boss.parts.client_statistics')
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				@include('boss.parts.new_tickets')
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				@include('parts.all_tickets',['deadline_cb'=>1])
			</div>
		</div>
	</div>
@endsection