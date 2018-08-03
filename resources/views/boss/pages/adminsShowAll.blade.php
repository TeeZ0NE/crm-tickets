@extends('boss.layout')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Админисистраторы</h5>
					<div class="card-body">
						<table class="table table-striped">
							<tr class="text-center">
								<th>#</th>
								<th>ID</th>
								<th>Имя</th>
								<th>Активен</th>
								<th class="text-right">Операции</th>
								u
							</tr>
							<tbody>@php $i=0;@endphp
							@foreach($admins as $admin)
								<tr>
									<td>{{++$i}}</td>
									<td>{{$admin->id}}</td>
									<td>{{$admin->name}}</td>
									<td class="text-center">
										@if ($admin->active) <i class="fas fa-plus"></i>
										@else <i class="fas fa-minus"></i>
										@endif
									</td>
									<td class="text-right">
										<form action="{{route('admins.destroy',$admin->id)}}" method="post">
											@method('DELETE')
											@csrf
											<button type="submit" class="btn btn-dark"
											        onclick="return confirm('Ви впевнені?')"><i
														class="fas fa-eraser"></i>
											</button>
											<button data-name="{{$admin->name}}" class="rename-admin btn btn-info">
												<i class="fas fa-pencil-alt"></i>
											</button>
										</form>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
		<script type="text/javascript">
			$(".rename-admin").on("click", function (event) {
				event.preventDefault();
				let old_name = $(this).attr('data-name');
				let new_name = prompt('Rename admin', old_name);
				if (new_name !== old_name && new_name !== null && new_name !== '') {
					$("input[name='_method']").val('PUT');
					$(this).parent().prepend("<input type=\"hidden\" name=\"name\" value=\"" + new_name + "\">");
					$(this).parent().submit();
				}
			});
		</script>
@endsection