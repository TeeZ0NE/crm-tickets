@if ($errors->any())
	<div class="container">
		<div class="row">
			<div class="col-12 alert alert-danger mt-3" role="alert">
				<ul class="list-unstyled">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
@endif

