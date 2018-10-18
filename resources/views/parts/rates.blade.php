<div class="card mt-3">
<h5 class="card-header">{{__('site.rate from',['from'=>$this_month->toDateString()])}} <a href="#" class="A4WJF float-right"><i class="far fa-plus-square"></i><i class="far fa-minus-square d-none"></i></a></h5>
<div class="card-body" style="display: none;">
@include('parts.rate',['statistic4AllAdmins'=>$statistic4AllAdmins])
</div>
</div>