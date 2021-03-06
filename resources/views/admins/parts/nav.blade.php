<nav class="navbar navbar-expand-md navbar-light bg-light">
<div class="container">
<a class="navbar-brand" href="{{route('home')}}">
{{ config('app.name', 'CRM tickets') }}
</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
aria-controls="navbarSupportedContent" aria-expanded="false"
aria-label="{{ __('Toggle navigation') }}">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<!-- Left Side Of Navbar -->
<ul class="navbar-nav mr-auto">
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="fas fa-bars"></i> {{__('site.menu')}}
</a>
<div class="dropdown-menu" aria-labelledby="navbarDropdown">
<a class="dropdown-item" href="{{route('admins.statistic')}}"><i
class="fas fa-list-ol"></i> @lang('site.rate')</a>
</div>
</li>
</ul>
<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
<!-- Authentication Links -->
@guest
<li class="nav-item">
<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
</li>
@else
<li class="nav-item dropdown">
<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
{{ Auth::user()->name }} <span class="caret"></span>
</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
<a class="dropdown-item" href="{{ route('logout') }}"
onclick="event.preventDefault();document.getElementById('logout-form').submit();">
<i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST"
style="display: none;">
@csrf
</form>
</div>
</li>
<li class="nav-item">
<a class="nav-link btn btn-outline-danger" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt fa-lg"></i></a>
</li>
@endguest
</ul>
</div>
</div>
</nav>