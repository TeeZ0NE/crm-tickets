<div class="sticky-top alert alert-light text-center h3 font-weight-bold" role="alert">
	@foreach($serviceCountOpenTickets as $service =>$val)
		<span class="{{$service}}-color">{{$service}}:</span>
	@php $valClass= $val['open_tickets']>0?"text-danger":"text-success";@endphp
		<span class={{$valClass}}>
			{{$val['open_tickets']}}
		</span>&nbsp;
		@endforeach
</div>