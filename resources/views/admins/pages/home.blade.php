@extends('admins.layout')

@section('main_content')

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 col-lg-3">left</div>
			<div class="col-md-8 col-lg-9">
				<div class="card mt-3">
					<h5 class="card-header">Новые тикеты</h5>
					<div class="card-body">
						<table class="table table-striped">
							<thead>
							<tr class="text-center">
								<th>{{__('site.waiting time')}}</th>
								<th>{{__('site.assign')}}</th>
								<th>{{__('site.service')}}</th>
								<th>{{__('site.ticket id')}}</th>
								<th>{{__('site.subject')}}</th>
								<th>{{__('site.last replier')}}</th>
								<th>{{__('site.last reply')}}</th>
								<th>{{__('site.priority')}}</th>
								<th>{{__('site.status')}}</th>
								<th>{{__('site.deadline')}}</th>
							</tr>
							</thead>
							<tbody>
							@foreach($newTickets as $newTicket)
								<tr>
									@php
										$waitingTime = \Carbon\Carbon::createFromTimeStamp(strtotime($newTicket->lastreply))->diffForHumans();
											if($newTicket->last_is_admin):
												foreach($newTicket->getAdmin as $admin):
												$lastReplier =$admin->name;
												endforeach;
											else:
												$lastReplier = $newTicket->getService['name']." client";
											endif;
									$user_id = Auth::id();
									@endphp
									<td>{{$waitingTime}}</td>
									<td  class="align-middle text-center">
										@if(!$newTicket->user_assign_id)
										<form action="{{route('admin.assign-ticket', ['id'=>$user_id, 'ticket_id'=>$newTicket->id])}}" method="POST">
											@csrf
											@method('POST')
										<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></button>
										</form>
											@else {{$newTicket->getUserAssignedTicket['name']}}
											@endif
									</td>
									<td  class="align-middle">{{$newTicket->getService->name}}</td>
									<td  class="align-middle">{{$newTicket->ticketid}}</td>
									<td>{{$newTicket->subject}}</td>
									<td>{{$lastReplier or __('site.unknown') }}</td>
									<td>{{$newTicket->lastreply}}</td>
									<td  class="align-middle">{{$newTicket->getPriority->priority}}</td>
									<td  class="align-middle">{{$newTicket->getStatus->name}}</td>
									<td>@if(isset($newTicket->getDeadline)){{$newTicket->getDeadline->deadline}}@else
											-- @endif</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="card mt-3">
					<h5 class="card-header">Мои тикеты</h5>
					<div class="card-body">
						<table class="table table-striped">
							<thead>
							<tr>
								<th>{{__('site.waiting time')}}</th>
								<th>{{__('site.service')}}</th>
								<th>{{__('site.ticket id')}}</th>
								<th>{{__('site.subject')}}</th>
								<th>{{__('site.last replier')}}</th>
								<th>{{__('site.last reply')}}</th>
								<th>{{__('site.priority')}}</th>
								<th>{{__('site.status')}}</th>
								<th>{{__('site.deadline')}}</th>
							</tr>
							</thead>
							<tbody>
							@if(isset($showMyTickets))
								@foreach($showMyTickets as $showMyTicket)
									<tr>
										@php
											$waitingTime = \Carbon\Carbon::createFromTimeStamp(strtotime($showMyTicket->lastreply))->diffForHumans();
												if($showMyTicket->last_is_admin):
													$lastReplier =$showMyTicket->getAdmin->first()['name']??'unknown';
												else:
													$lastReplier = $showMyTicket->getService['name']. ' client';
												endif;
										@endphp
										<td>{{$waitingTime}}</td>
										<td>{{$showMyTicket->getService->name}}</td>
										<td>{{$showMyTicket->ticketid}}</td>
										<td>{{$showMyTicket->subject}}</td>
										<td>{{$lastReplier or "Невідомо"}}</td>
										<td>{{$showMyTicket->lastreply}}</td>
										<td>{{$showMyTicket->getPriority->priority}}</td>
										<td>{{$showMyTicket->getStatus->name}}</td>
										<td>@if(isset($newTicket->getDeadline)){{$newTicket->getDeadline->deadline}}@else
												-- @endif</td>
									</tr>
								@endforeach
							@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
