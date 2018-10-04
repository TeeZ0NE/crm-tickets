@extends('boss.layout')
@section('title','Список рассылки')
@section('main_content')
<div class="container">
<div class="row">
<div class="col-md-12 mb-md-2 mb-lg-3">
<div class="card">
<h5 class="card-header">Редактировать список рассылки</h5>
<div class="card-body">
<form action="{{route('email-lists.update',['id'=>$mailable_id])}}" class="justify-content-center form-inline" method="post">
@csrf
@method('PUT')
<div class="form-group">
@include('boss.parts.servicesList')
@include('boss.parts.intervalsList')
@include('boss.parts.emailList')
<button type="submit" class="btn btn-primary">Сохранить</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection