<div class="card mt-3">
	<h5 class="card-header">{{__('site.rate from to',['from'=>$month,'to'=>$endMonth])}}
	</h5>
	<div class="card-body">
		@include('parts.rate',['statistic4AllAdmins'=>$statistic4AllAdmins])
	</div>
</div>