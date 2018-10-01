<form method="post" class="form-inline justify-content-center" action="{{route('services.sendStatistic',['service_id'=>$service_id,'interval'=>$interval])}}">
@csrf()
<label for="emailSelect">Получатели</label>
<select name="emails[]" multiple class="form-control mx-2" id="emailSelect" size="5">
@foreach($emailList as $email)
<option value="{{$email->id}}">
{{$email->email}}
</option>
@endforeach
</select>
<button class="btn btn-primary">Отправить на e-mail</button>
</form>