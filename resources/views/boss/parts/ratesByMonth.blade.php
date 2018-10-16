<div class="card mt-3">
	<h5 class="card-header">{{__('site.rate from to',['from'=>$month,'to'=>$endMonth])}}
		<a href="#" class="A4WJF"><i class="far fa-plus-square float-right d-none"></i><i class="far fa-minus-square float-right"></i></a></h5>
	<div class="card-body">
		@include('parts.rate',['statistic4AllAdmins'=>$statistic4AllAdmins])
	</div>
</div>