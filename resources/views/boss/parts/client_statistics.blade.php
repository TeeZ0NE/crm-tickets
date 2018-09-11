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
					<tr class="@empty($value['is_available'])lastreply-max @endempty text-center">
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