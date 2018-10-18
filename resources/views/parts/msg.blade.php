@if(Session::has('msg'))
<div class="container">
<div class="row">
<p class="col-12 mt-3 alert alert-success" role="alert">{{Session::get('msg')}}</p>
</div>
</div>
@endif
