@foreach($statistic4AllAdmins as $services =>$serviceDatas)
	@if(!$serviceDatas->isEmpty())
		<h2>{{$services}}</h2>
		<table class="table">
			<thead>
			<tr>
				<th>{{__('site.user_name')}}</th>
				<th>{{__('site.tickets count')}}</th>
				<th>{{__('site.replies count')}}</th>
				<th>{{__('site.time summary')}}</th>
				<th>{{__('site.rate')}}</th>
			</tr>
			</thead>
			<tbody>
			@foreach($serviceDatas as $service)
				<tr>
					<td>{{$service->user_name}}</td>
					<td>{{$service->tickets_count}}</td>
					<td>{{$service->replies_count}}</td>
					<td>{{sprintf('%02d:%02d',floor($service->sum_time/60),$service->sum_time%60)}}</td>
					<td>{{$service->rate}}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	@endif
@endforeach