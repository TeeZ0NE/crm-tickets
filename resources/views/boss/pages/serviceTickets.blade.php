@extends('boss.layout')
@section('title','Тикеты')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Фильтр</h5>
					<div class="card-body">
						<form action="{{route('tickets.search')}}"
						      class="justify-content-center form-inline">
							<div class="form-group">
								@include('boss.parts.servicesList')
								<div class="input-group ml-2 mr-2">
									<div class="input-group-prepend">
										<span class="input-group-text" id="ticketid-addon">Ticketid</span>
									</div>
									<input type="text" class="form-control" placeholder="Ticketid" aria-label="Ticketid" aria-describedby="ticketid-addon" name="ticketid" value="@isset($ticketid) {{$ticketid}} @endisset">
								</div>
								<button type="submit" class="btn btn-primary">Получить</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

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
						Всього: {{ $total}} на сторінці: {{ $tickets->count() }}{{$links}}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection