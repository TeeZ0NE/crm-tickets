@component('mail::message')
# Регистрационные данные {{$name}}!

Email: {{$email}}<br/>
Пароль: {{$password}}

@component('mail::button', ['url' => $url])
Перейти
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

