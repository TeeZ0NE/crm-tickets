<form action="{{route('admins.statistics_subMonths')}}" method="GET">
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text" id="month-addon">Месяцев</span>
		</div>
		<input type="number" class="form-control" placeholder="Кол-во месяцев" aria-label="month" aria-describedby="name-addon" name="month_count" value="{{$month_count}}" min="1">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" id="curr-month" name="curr_month"  @if($curr_month) checked @endif>
			<label class="form-check-label" for="curr-month">Текущий месяц</label>
		</div>
		<button type="submit" class="btn btn-success"><i class="fas fa-play"></i></button>
	</div>
</form>