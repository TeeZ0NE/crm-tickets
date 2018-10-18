@extends('boss.layout')
@section('main_content')
<form action="{{route('admins.store')}}" method="POST">{{csrf_field()}}
<div class="container">
<div class="row">
<div class="col-lg-6 mx-auto">
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="name-addon">Имя</span>
</div>
<input type="text" class="form-control" placeholder="Имя администратора" aria-label="name"
aria-describedby="name-addon" name="name" value="{{old('name')}}">
</div>
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="email-addon">@</span>
</div>
<input type="email" class="form-control" placeholder="email" aria-label="email"
aria-describedby="email-addon" name="email" value="{{old('email')}}">
</div>
<div class="input-group mb-3">
<input type="text" class="form-control pass-gen" placeholder="Сгенерировать пароль"
aria-label="Recipient's username" aria-describedby="pass-gen" readonly>
<div class="input-group-append">
<button class="btn btn-outline-secondary" type="button" id="pass-gen"><i
class="fas fa-random"></i></button>
</div>
</div>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">Пароль и его подтверждение</span>
</div>
<input type="password" aria-label="password" class="form-control" name="password">
<input type="password" aria-label="confirm" class="form-control" name="confirm">
</div>
</div>
<div class="col-12 text-center">
<button type="submit" class="btn btn-success mt-3"><i class="far fa-save"></i></button>
</div>
</div>
</div>
</form>
<script type="text/javascript">
$('#pass-gen').on('click', function () {
$('.pass-gen').val(Math.random().toString(36).substring(6));
})
</script>
@endsection