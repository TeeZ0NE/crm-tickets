@extends('boss.layout')
@section('title','Клиенты')
@section('main_content')
<div class="container">
<div class="row">
<div class="col-md-12 mb-md-2 mb-lg-3">
<div class="card">
<h5 class="card-header">Клиенты (тикетницы)</h5>
<div class="card-body">
<form action="{{route('services.create')}}" method="post">
@csrf
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">Клиент, сложность</span>
</div>
<input type="text" aria-label="name" class="form-control"
value="{{old('name')}}" name="name">
<input type="number" aria-label="compl" class="form-control"
value="@php echo old('compl')??1.0; @endphp" step=".1" name="compl" max="9.9" min="0">
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