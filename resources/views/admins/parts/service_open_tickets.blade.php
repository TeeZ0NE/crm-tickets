<div class="sticky-top alert alert-light text-center h3 font-weight-bold" role="alert">
	@foreach($serviceCountOpenTickets as $service =>$val)
			<a href="{{$val['home_link']}}" target="_blank">
				<span class="{{$service}}-color">{{$service}}:</span>
			</a>
	@php $valClass= $val['open_tickets']>0?"text-danger":"text-success";@endphp
		<span class={{$valClass}}>
			{{$val['open_tickets']}}
		</span>&nbsp;
		@endforeach
</div>