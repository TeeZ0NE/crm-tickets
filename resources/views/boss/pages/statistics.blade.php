@extends('boss.layout')
@section('title','Статистика')
@section('main_content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-md-2 mb-lg-3">
				<div class="card mt-3">
					<h5 class="card-header">{{__('site.rate_from')}} {{$fromStartOfMonth}}</h5>
					<div class="card-body">
						<table class="table">
							<thead>
							<tr>
								<th>{{__('site.user_name')}}</th>
								<th>{{__('site.tickets count')}}</th>
								<th>{{__('site.replies count')}}</th>
								<th>{{__('site.time summary')}}</th>
							</tr>
							</thead>
							<tbody>
							@foreach($statistic4AllAdmins as $admin)
								<tr>
									<td>{{$admin->name}}</td>
									<td>{{$admin->tickets_count}}</td>
									<td>{{$admin->replies_count}}</td>
									<td>{{sprintf('%02d:%02d',floor($admin->time_sum/60),$admin->time_sum%60)}}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection