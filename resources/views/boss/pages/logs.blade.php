@extends('boss.layout')
@section('title','Логи')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Логи с сервера</h5>
					<div class="card-body">
						<pre>{{$logs}}</pre>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection