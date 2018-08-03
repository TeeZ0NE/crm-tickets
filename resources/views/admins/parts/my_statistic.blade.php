<div class="card mt-3">
	<h5 class="card-header">Моя статистика</h5>
	<div class="card-body">
		<table class="table">
			<thead>
			<tr>
				<th>{{__('site.tickets count')}}</th>
				<th>{{__('site.replies count')}}</th>
				<th>{{__('site.time summary')}}</th>
				<th>{{__('site.compl')}}</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>{{$showMyStatistic['tickets_count']}}</td>
				<td>{{$showMyStatistic['replies_count']}}</td>
				<td>{{$showMyStatistic['using_time']}}</td>
				<td>{{$showMyStatistic['compl']}}</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>