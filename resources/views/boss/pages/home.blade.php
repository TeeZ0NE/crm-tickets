@extends('boss.layout')
@section('title','Boss Home')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Кол-во тикетов</h5>
					<div class="card-body">
						<table class="table table-striped">
							<thead>
							<tr class="text-center">
								<th>Service</th>
								<th>count open tickets</th>
								<th>summary count of tickets</th>
								<th>from yesterday</th>
								<th>from month start</th>
							</tr>
							</thead>
							<tbody>
							@if(isset($ticketCounts))
								@foreach($ticketCounts as $service => $value)
									<tr class="text-center">
										<td class="text-left">{{$service}}</td>
										<td>{{$value['open_tickets']}}</td>
										<td>{{$value['summary_tickets']}}</td>
										<td>{{$value['yesterday']}}</td>
										<td>{{$value['start_month']}}</td>
									</tr>
								@endforeach
							@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
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

									@endphp
									<td>{{$waitingTime}}</td>
									<td>
										@if($newTicket->user_assign_id)
											{{$newTicket->getUserAssignedTicket['name']}}
											@else Свободен
										@endif
									</td>
									<td>{{$newTicket->getService->name}}</td>
									<td>{{$newTicket->ticketid}}</td>
									<td>{{$newTicket->subject}}</td>
									<td>{{$lastReplier or __('site.unknown')}}</td>
									<td>{{$newTicket->lastreply}}</td>
									<td>{{$newTicket->getPriority->priority}}</td>
									<td>{{$newTicket->getStatus->name}}</td>
									<td>@if(isset($newTicket->getDeadline)){{$newTicket->getDeadline->deadline}}@else
											-- @endif</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card">
					<h5 class="card-header">Все тикеты</h5>
					<div class="card-body">
						<table class="table table-striped all-tickets">
							<thead>
							<tr class="text-center">
								<td>#</td>
								<th>{{__('site.waiting time')}}</th>
								<th>{{__('service')}}</th>
								<th>{{__('ticket id')}}</th>
								<th>{{__('site.subject')}}</th>
								<th>{{__('site.last replier')}}</th>
								<th>{{__('site.last reply')}}</th>
								<th>{{__('site.assign')}}</th>
								<th>{{__('site.priority')}}</th>
								<th>{{__('site.status')}}</th>
								<th>{{__('site.deadline')}}</th>
							</tr>
							</thead>
							<tbody>
							@if(isset($openTickets))
								@php $i=1;@endphp
								@foreach($openTickets as $openTicket)
									<tr class="align-middle">
										@php
											$waitingTime = \Carbon\Carbon::createFromTimeStamp(strtotime($openTicket->lastreply))->diffForHumans();
												if($openTicket->last_is_admin):
													$lastReplier = ($openTicket->getAdmin->first()['name'])
													?$openTicket->getAdmin->first()['name']
													:"Please bind with admin_nik_id $openTicket->last_replier_nik_id";
												else:
													$lastReplier = $openTicket->getService['name']." client";
												endif;
												$ticket_owner = ($openTicket->last_replier_nik_id)
												?$openTicket->getAdmin->first()['name']
												:'Новий';
										@endphp
										<td>{{$i++}}</td>
										<td>{{$waitingTime}}</td>
										<td>{{$openTicket->getService->name}}</td>
										<td>{{$openTicket->ticketid}}</td>
										<td>{{$openTicket->subject}}</td>
										<td>{{$lastReplier or __('site.unknown')}}</td>
										<td>{{$openTicket->lastreply}}</td>
										<td>{{$ticket_owner or __('site.unknown')}}</td>
										<td>{{$openTicket->getPriority->priority or __('site.unknown')}}</td>
										<td>{{$openTicket->getStatus->name or __('site.unknown')}}</td>
										<td>{{$openTicket->getDeadline->deadline or __('site.unknown')}}</td>
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