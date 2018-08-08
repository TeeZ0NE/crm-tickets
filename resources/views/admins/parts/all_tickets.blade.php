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
		</tr>
		</thead>
		<tbody>
		@if(isset($openTickets))
			@php $i=1;@endphp
			@foreach($openTickets as $openTicket)
				@php
					$lastreply_class = setClass4lastreply($openTicket, $deadlineList,$maxDeadline);
				@endphp
				<tr class="align-middle {{$lastreply_class or ''}}">
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
				</tr>
			@endforeach
		@endif
		</tbody>
	</table>
</div>
</div>