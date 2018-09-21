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
			</tr>
			</thead>
			<tbody>
			@php
				$Carbon::setLocale(App::getLocale());
			@endphp
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
					<td class="align-middle">
						<a href="{{$newTicket->getService->href_link}}{{$newTicket->ticketid}}" target="_blank" class="btn btn-info">{{$newTicket->ticketid}}</a>
					</td>
					<td>{{$newTicket->subject}}</td>
					<td>{{$lastReplier}}</td>
					<td>{{$newTicket->lastreply}}</td>
					<td class="align-middle">{{$newTicket->getPriority->priority}}</td>
					<td class="align-middle">{{$newTicket->getStatus->name}}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>