<label for="emailSelect">Получатели</label>
<select name="emails[]" multiple class="form-control mx-2" id="emailSelect" size="5">
@foreach($emails as $email)
<option value="{{$email->id}}" @if(isset($emails_ids) and in_array($email->id,$emails_ids)) selected @endif>
{{$email->email}}
</option>
@endforeach
</select>