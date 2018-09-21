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
			</tr>
			</thead>
			<tbody>
			@foreach($newTickets as $newTicket)
				@php
					$lastreply_class = setClass4lastreply($newTicket, $deadlineList,$maxDeadline);
				@endphp
				<tr @isset($lastreply_class) class="{{$lastreply_class}}" @endisset>
					@php
						$lastReplier = $newTicket->getService['name']." client";
							$waitingTime = $Carbon::createFromTimeStamp(strtotime($newTicket->lastreply))->diffForHumans();
								if($newTicket->last_is_admin):
									foreach($newTicket->getAdmin as $admin):
									$lastReplier =$admin->name;
									endforeach;
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
					<td>
						<a href="{{$newTicket->getService->href_link}}{{$newTicket->ticketid}}" target="_blank"
						   class="btn btn-info">{{$newTicket->ticketid}}</a>
					</td>
					<td>{{$newTicket->subject}}</td>
					<td>{{$lastReplier}}</td>
					<td>{{$newTicket->lastreply}}</td>
					<td>{{$newTicket->getPriority->priority}}</td>
					<td>{{$newTicket->getStatus->name}}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>