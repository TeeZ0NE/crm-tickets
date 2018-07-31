@extends('admins.layout')
@section('title','Admin Home')
@section('main_content')

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 col-lg-3">
				<div class="sticky-top">
				<div class="card mt-3">
					<h5 class="card-header">Моя статистика</h5>
					<div class="card-body">
						<table class="table">
							<thead>
							<tr>
								<th>{{__('site.tickets count')}}</th>
								<th>{{__('site.replies count')}}</th>
								<th>{{__('site.time summary')}}</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>{{$showMyStatistic['tickets_count']}}</td>
								<td>{{$showMyStatistic['replies_count']}}</td>
								<td>{{$showMyStatistic['using_time']}}</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card mt-3">
					<h5 class="card-header">{{__('site.rate_from')}} <small>{{$Carbon::now()->startOfMonth()}}</small></h5>
					<div class="card-body">
						<table class="table">
							<thead>
							<tr>
								<th>{{__('site.user_name')}}</th>
								<th>{{__('site.tickets count')}}</th>
								<th>{{__('site.replies count')}}</th>
								<th>{{__('site.time summary')}}</th>
							</tr>
							</thead>
							<tbody>
							@foreach($statistic4AllAdmins as $admin)
							<tr>
								<td>{{$admin->name}}</td>
								<td>{{$admin->tickets_count}}</td>
								<td>{{$admin->replies_count}}</td>
								<td>{{sprintf('%02d:%02d',floor($admin->time_sum/60),$admin->time_sum%60)}}</td>
							</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
				</div>
			</div>
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
							@php
								$Carbon::setLocale(App::getLocale());
							@endphp
							@foreach($newTickets as $newTicket)
								<tr>
									@php
										$waitingTime = $Carbon::createFromTimeStamp(strtotime($newTicket->lastreply))->diffForHumans();
											if($newTicket->last_is_admin):
												foreach($newTicket->getAdmin as $admin):
												$lastReplier =$admin->name;
												endforeach;
											else:
												$lastReplier = $newTicket->getService['name']." client";
											endif;
									@endphp
									<td>{{$waitingTime}}</td>
									<td class="align-middle text-center">
										@if(!$newTicket->user_assign_id)
											<form action="{{route('admin.assign-ticket', ['id'=>$user_id, 'ticket_id'=>$newTicket->id])}}"
											      method="POST">
												@csrf
												@method('POST')
												<button type="submit" class="btn btn-primary btn-sm"><i
															class="fas fa-plus"></i></button>
											</form>
										@else {{$newTicket->getUserAssignedTicket['name']}}
										@endif
									</td>
									<td class="align-middle">{{$newTicket->getService->name}}</td>
									<td class="align-middle">{{$newTicket->ticketid}}</td>
									<td>{{$newTicket->subject}}</td>
									<td>{{$lastReplier or __('site.unknown') }}</td>
									<td>{{$newTicket->lastreply}}</td>
									<td class="align-middle">{{$newTicket->getPriority->priority or __('site.unknown')}}</td>
									<td class="align-middle">{{$newTicket->getStatus->name or __('site.unknown')}}</td>
									{{--<td>@if(isset($newTicket->getDeadline)){{$newTicket->getDeadline->deadline}}@else--}}
									{{---- @endif</td>--}}
									<td>{{$newTicket->getDeadline or __('site.unknown')}}</td>
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
											$waitingTime = $Carbon::createFromTimeStamp(strtotime($showMyTicket->lastreply))->diffForHumans();
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
										<td>{{$lastReplier or __('site.unknown')}}</td>
										<td>{{$showMyTicket->lastreply}}</td>
										<td>{{$showMyTicket->getPriority->priority or __('site.unknown')}}</td>
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
				<div class="card mt-3">
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
										$waitingTime = $Carbon::createFromTimeStamp(strtotime($openTicket->lastreply))->diffForHumans();
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
