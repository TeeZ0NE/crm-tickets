<select class="custom-select" id="service" name="service_id">
@foreach($services as $service)
<option value="{{$service->id}}"
@if(isset($service_id) and $service->id == $service_id)selected @endif>
{{$service->name}}
</option>
@endforeach
</select>