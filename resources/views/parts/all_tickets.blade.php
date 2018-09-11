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
				@isset($deadline_cb)
				<th>{{__('site.deadline')}}</th>
				@endisset
			</tr>
			</thead>
			<tbody>
			@if(isset($openTickets))
				@php $i=1;
				@endphp
				@foreach($openTickets as $openTicket)
					@php
						$lastreply_class = setClass4lastreply($openTicket, $deadlineList,$maxDeadline);
					@endphp
					<tr class="align-middle {{$lastreply_class}}">
						@php
							$is_new = '';
							$waitingTime = $Carbon::createFromTimeStamp(strtotime($openTicket->lastreply))->diffForHumans();
								if($openTicket->last_is_admin):
									$lastReplier = ($openTicket->getAdmin->first()['name'])
									?$openTicket->getAdmin->first()['name']
									:"Please bind with admin_nik_id $openTicket->last_replier_nik_id";
								else:
									$lastReplier = $openTicket->getService['name']." <b>client</b>";
								endif;
								if (!$openTicket->last_replier_nik_id) $is_new=__('site.new');
						@endphp
						<td>{{$i++}}</td>
						<td>{{$waitingTime}}</td>
						<td>{{$openTicket->getService->name}}</td>
						<td><a href="{{$openTicket->getService->href_link}}{{$openTicket->ticketid}}" target="_blank" class="btn btn-info">{{$openTicket->ticketid}}</a></td>
						<td>{{$openTicket->subject}}</td>
						<td>{!! $lastReplier !!}@if((bool)$is_new)<br><u>{{$is_new}}</u>@endif</td>
						<td>{{$openTicket->lastreply}}</td>
						<td>{{$openTicket->getUserAssignedTicket['name']}}</td>
						<td>{{$openTicket->getPriority->priority}}</td>
						<td>{{$openTicket->getStatus->name}}</td>
						@isset($deadline_cb)
						<td>
							<form action="{{route('boss.ticket.update',$openTicket->id)}}" method="post">
								@csrf
								@method('PUT')
								<input type="checkbox" name="has_deadline" class="submit" @if($openTicket->has_deadline)checked @endif>
							</form>
						</td>
							@endisset
					</tr>
				@endforeach
			@endif
			</tbody>
		</table>
	</div>
</div>
@push('js-scripts')
	<script type="text/javascript" src="{{asset('js/submitOnCheckbox.min.js')}}"></script>
 @endpush