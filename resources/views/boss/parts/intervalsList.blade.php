<select class="custom-select ml-2 mr-2" id="interval" name="interval">
@foreach($intervals as $interval_fe)
<option value="{{$interval_fe->url_attr}}" @if(isset($interval) and $interval_fe->url_attr==$interval) selected @endif>{{$interval_fe->name}}</option>
@endforeach
</select>