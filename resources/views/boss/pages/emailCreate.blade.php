@extends('boss.layout')
@section('title','E-mail создать')
@section('main_content')
<div class="container">
<div class="row">
<div class="col-md-12 mb-md-2 mb-lg-3">
<div class="card">
<h5 class="card-header">Почтовый ящик</h5>
<div class="card-body">
<form action="{{route('emails.store')}}" method="post">
@csrf
<div class="input-group w-50 m-auto">
<input type="email" aria-label="email" class="form-control"
value="{{old('email')}}" name="email">
<button type="submit" class="store-service btn btn-info">
<i class="fas fa-save"></i>
</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection