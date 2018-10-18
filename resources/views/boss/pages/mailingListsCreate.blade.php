@extends('boss.layout')
@section('title','Список рассылки')
@section('main_content')
<div class="container">
<div class="row">
<div class="col-md-12 mb-md-2 mb-lg-3">
<div class="card">
<h5 class="card-header">Создать список рассылки</h5>
<div class="card-body">
<form action="{{route('email-lists.store')}}"
class="justify-content-center form-inline" method="post">
@csrf
<div class="form-group">
@include('boss.parts.servicesList')
@include('boss.parts.intervalsList')
@include('boss.parts.emailList')
<button type="submit" class="btn btn-primary">Создать</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection