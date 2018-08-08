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
					@php
						$lastreply_class = setClass4lastreply($showMyTicket, $deadlineList,$maxDeadline);
					@endphp
					<tr @isset($lastreply_class) class="{{$lastreply_class}}" @endisset>
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
						<td><form action="{{route('boss.ticket.update',$showMyTicket->id)}}" method="post">
								@csrf
								@method('PUT')
								<input type="checkbox" name="has_deadline" @if($showMyTicket->has_deadline)checked @endif>
							</form></td>
					</tr>
				@endforeach
			@endif
			</tbody>
		</table>
	</div>
</div>
<script>
	$('input[type="checkbox"]').on('click', function () {
		$(this).parent().submit();
	})
</script>