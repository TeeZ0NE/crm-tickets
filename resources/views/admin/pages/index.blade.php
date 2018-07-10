@extends('admin.layout')
@section('main_content')
	<h3>Admin niks and services</h3>
	<table border="1">
		<thead>
		<tr>
			<th># nik / real</th>
			<th>Service</th>
			<th>Admin nik</th>
			<th>real name</th>
			<th>Complicate</th>
		</tr>
		</thead>
		<tbody>
		@foreach($adminNiks as $adminNik)
			<tr>
				<td>{{$adminNik->admin_nik_id}}/{{$adminNik->getRealAdmin['id']}}</td>
				<td>{{$adminNik->getService['name']}}</td>
				<td>{{$adminNik->admin_nik}}</td>
				<td>{{$adminNik->getRealAdmin['name']}}</td>
				<td>{{$adminNik->getService['compl']}}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<h3>Admin and service and niks</h3>
	<table border="1">
		<thead>
		<tr>
			<th>#</th>
			<th>real admin</th>
			<th>nik</th>
			<th>service</th>
		</tr>
		</thead>
		<tbody>
		@foreach($adminNiksVV as $admin)
			<tr>
				<td>{{$admin->id}}</td>
				<td>{{$admin->name}}</td>
				<td>@foreach($admin->getNiks as $nik){{$nik->admin_nik}}<br>@endforeach</td>
				<td>@foreach($admin->getServices as $service){{$service->name}}
					<small>({{$service->compl}})</small><br>@endforeach</td>
			</tr>
		@endforeach
		</tbody>
	</table>
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
	<h3>tickets</h3>
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
						endif
				@endphp
			<td>{{$waitingTime}}</td>
				<td>{{$openTicket->getService->name}}</td>
				<td>{{$openTicket->ticketid}}</td>
				<td>{{$openTicket->subject}}</td>
				<td>{{$lastReplier}}</td>
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
