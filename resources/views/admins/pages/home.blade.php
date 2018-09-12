@extends('admins.layout')
@section('title','Admin Home')
@section('main_content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 col-lg-3">
				<div class="sticky-top">
					@include('admins.parts.my_statistic')
					@include('parts.rates')
				</div>
			</div>
			<div class="col-md-8 col-lg-9">
				@include('admins.parts.service_open_tickets')
				@include('admins.parts.new_tickets')
				@include('admins.parts.my_tickets')
				@include('parts.all_tickets')
			</div>
		</div>
	</div>
@endsection
