@extends('admins.layout')
@section('main_content')
<h3>Admins</h3>
<p><a href="{{route('admins.index')}}">show all real admins</a></p>

	<p>
		<a href="{{route('admins.nicks')}}">Bind Admin Nik with real admin</a>
	</p>

	<h3>Tickets Count</h3>
	<table border="1">
		<thead>
		<tr>
			<th>Service</th>
			<th>count open tickets</th>
			<th>summary count of tickets</th>
			<th>from yesterday</th>
			<th>from month start</th>
		</tr>
		</thead>
		<tbody>
		@foreach($ticketCounts as $service => $value)
			<tr>
				<td>{{$service}}</td>
				<td>{{$value['open_tickets']}}</td>
				<td>{{$value['summary_tickets']}}</td>
				<td>{{$value['yesterday']}}</td>
				<td>{{$value['start_month']}}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<h3>new tickets</h3>
	<table border="1">
		<thead>
		<tr>
			<th>waiting time</th>
			<th>Service</th>
			<th>ticket id</th>
			<th>Subject</th>
			<th>last replier</th>
			<th>last reply</th>
			<th>Priority</th>
			<th>Status</th>
			<th>Deadline</th>
		</tr>
		</thead>
		<tbody>
		@foreach($newTickets as $newTicket)
			<tr>
				@php
					$waitingTime = \Carbon\Carbon::createFromTimeStamp(strtotime($newTicket->lastreply))->diffForHumans();
						if($newTicket->last_replier_nik_id):
							foreach($newTicket->getAdmin as $admin):
							$lastReplier =$admin->name;
							endforeach;
						else:
							$lastReplier = $newTicket->getService['name']." client";
						endif;

				@endphp
			<td>{{$waitingTime}}</td>
				<td>{{$newTicket->getService->name}}</td>
				<td>{{$newTicket->ticketid}}</td>
				<td>{{$newTicket->subject}}</td>
				<td>{{$lastReplier or "Невідомо"}}</td>
				<td>{{$newTicket->lastreply}}</td>
				<td>{{$newTicket->getPriority->priority}}</td>
				<td>{{$newTicket->getStatus->name}}</td>
				<td>@if(isset($newTicket->getDeadline)){{$newTicket->getDeadline->deadline}}@else -- @endif</td>
			</tr>
		@endforeach
		</tbody>
	</table>

	<h3>all tickets</h3>
	<table border="1">
		<thead>
		<tr>
			<th>waiting time</th>
			<th>Service</th>
			<th>ticket id</th>
			<th>Subject</th>
			<th>last replier</th>
			<th>last reply</th>
			<th>Priority</th>
			<th>Status</th>
			<th>Deadline</th>
		</tr>
		</thead>
		<tbody>
		@foreach($openTickets as $openTicket)
			<tr>
				@php
					$waitingTime = \Carbon\Carbon::createFromTimeStamp(strtotime($openTicket->lastreply))->diffForHumans();
						if($openTicket->last_replier_nik_id):
							foreach($openTicket->getAdmin as $admin):
							$lastReplier =$admin->name;
							endforeach;
						else:
							$lastReplier = $openTicket->getService['name']." client";
						endif;

				@endphp
			<td>{{$waitingTime}}</td>
				<td>{{$openTicket->getService->name}}</td>
				<td>{{$openTicket->ticketid}}</td>
				<td>{{$openTicket->subject}}</td>
				<td>{{$lastReplier or "Невідомо"}}</td>
				<td>{{$openTicket->lastreply}}</td>
				<td>{{$openTicket->getPriority->priority}}</td>
				<td>{{$openTicket->getStatus->name}}</td>
				<td>@if(isset($openTicket->getDeadline)){{$openTicket->getDeadline->deadline}}@else -- @endif</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<h3>Count of closed tickets and replies by admin</h3>
	<table border="1">
		<thead>
		<tr>
			<th>admin name</th>
			<th>closed ticket C</th>
			<th>replies</th>
		</tr>
		</thead>
		<tbody>
		@foreach($countOfClosedAndReplies as $countOfClosedAndReply)
		<tr>
			<td>{{$countOfClosedAndReply->name}}</td>
			<td>{{$countOfClosedAndReply->ticket_count}}</td>
			<td>{{$countOfClosedAndReply->reply_count}}</td>
		</tr>
			@endforeach
		</tbody>
	</table>
@endsection
