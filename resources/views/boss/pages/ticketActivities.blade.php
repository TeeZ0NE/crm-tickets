@extends('boss.layout')
@section('title','Тикеты')
@section('main_content')
<div class="container">
<div class="row">
<div class="col-md-12 mb-md-2 mb-lg-3">
<div class="card">
<h5 class="card-header">{{$service}} <a
href="{{$href_link}}{{$ticketid}}">{{$ticketid}}</a> {{$subject}}</h5>
<div class="card-body">
<table class="table table-striped">
<tr class="text-center">
<th>Admin (Nik)</th>
<th>lastreply</th>
<th>time_uses</th>
<th></th>
</tr>
<tbody>
@forelse($activities as $activity)
<tr>
<td>{{$activity->user_name}}
<small>({{$activity->admin_nik}})</small>
</td>
<td>{{$activity->lastreply}}</td>
<td>{{$activity->time_uses}}</td>
<td>
<form action="{{route('activity.delete',$activity->sact_id)}}" method="post">
@method('DELETE')
@csrf
<button type="submit" class="btn btn-dark"
onclick="return confirm('Ви впевнені?')"><i
class="fas fa-eraser"></i>
</button>
</form>
</td>
</tr>
@empty
<tr>
<td colspan="4">Nothing</td>
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