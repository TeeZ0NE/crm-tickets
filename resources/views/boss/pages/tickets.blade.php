@extends('boss.layout')
@section('title','Тикеты')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Тикеты</h5>
					<div class="card-body">
						<table class="table table-striped">
							<tr class="text-center">
								<th>ID</th>
								<th>Client</th>
								<th>ticketid</th>
								<th>Subject</th>
								<th></th>
							</tr>
							<tbody>
							@foreach($tickets as $ticket)
								<tr>
									<td class="align-middle">{{$ticket->id}}</td>
									<td>{{$ticket->getService->name}}</td>
									<td><a href="{{$ticket->getService->href_link}}{{$ticket->ticketid}}">{{$ticket->ticketid}}</a></td>
									<td>{{$ticket->subject}}</td>
									<td>
										<form action="{{route('boss.ticket.destroy',$ticket->id)}}" method="post">
											@method('DELETE')
											@csrf
											<button type="submit" class="btn btn-dark"
											        onclick="return confirm('Ви впевнені?')"><i
														class="fas fa-eraser"></i>
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
@endsection