@extends('boss.layout')
@section('title','Списки рассылки')
@section('main_content')
<div class="container">
<div class="row">
<div class="col-md-12 mb-md-2 mb-lg-3">
<div class="card">
<h5 class="card-header">Списки рассылки</h5>
<div class="card-body">
<table class="table table-striped">
<thead>
<tr class="text-center">
<th>ID</th>
<th>Клиент</th>
<th>Период</th>
<th>Адресаты</th>
<th>Операции</th>
</tr>
</thead>
<tbody>
@forelse($mailables as $mailable)
<tr>
<td>{{$mailable->id}}</td>
<td>{{$mailable->getService->name}}</td>
<td>{{$mailable->getInterval->name}}</td>
<td>
@forelse($mailable->getEmails as $email)
<a href="mailto:{{$email->email}}">{{$email->email}}</a>@if (!$loop->last), @endif
@empty <p>Получателей нет в этом интервале для клиента</p>
</td>
@endforelse
<td>
<form action="{{route('email-lists.destroy',$mailable->id)}}" method="post">
@method('DELETE')
@csrf
<a href="{{route('email-lists.edit',$mailable->id)}}"
class="btn btn-info"><i class="fas fa-pencil-alt"></i></a>
<button type="submit" class="btn btn-dark" onclick="return confirm('Ви впевнені?')"><i class="fas fa-eraser"></i></button>
</form>
</td>
@empty
<td>Список пустой</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
@endsection