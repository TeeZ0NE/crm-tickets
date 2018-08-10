<div class="card mt-3">
<h5 class="card-header">{{__('site.rate_from')}}
	<small>{{$this_month}}</small>
</h5>
<div class="card-body">
	@include('parts.rate',['statistic4AllAdmins'=>$statistic4AllAdmins])
</div>
</div>