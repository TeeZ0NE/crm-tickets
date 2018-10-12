@isset($summary->name)Тикетов: {{$summary->tickets_count}} Время: {{$total4humans}}&nbsp;
<small>({{$summary->sum_time}} мин.)</small>@endisset
<table class="table table-striped all-tickets">
<thead>
<tr class="text-center">
<th>ticketid</th>
<th>Тема</th>
<th>Время</th>
</tr>
</thead>
<tbody>
@if(gettype($statistics)==='object')
@foreach($statistics as $statistic)
<tr>
<td><a href="{{$statistic->href_link}}{{$statistic->ticketid}}" target="_blank">{{$statistic->ticketid}}</a></td>
<td>{{$statistic->subject}}</td>
<td>{{$statistic->sum_time}}</td>
</tr>
@endforeach
@else
<tr>
<td colspan="3" class="text-center">{{$statistics}}</td>
</tr>
@endif
</tbody>
@isset($summary->name)
<tfoot>
<tr>
<td colspan="3" class="font-weight-bold">Итого:</td>
</tr>
<tr class="table-info font-weight-bold text-right">
<td>Клиент: {{$summary->name}}</td>
<td>Тикетов: {{$summary->tickets_count}}</td>
<td>{{$total4humans}}&nbsp;<small>({{$summary->sum_time}} мин.)</small>
</td>
</tr>
</tfoot>
@endisset
</table>