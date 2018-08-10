<div class="card mt-3">
<h5 class="card-header">{{__('site.rate from',['from'=>$this_month->toDateString()])}}</h5>
<div class="card-body">
	@include('parts.rate',['statistic4AllAdmins'=>$statistic4AllAdmins])
</div>
</div>