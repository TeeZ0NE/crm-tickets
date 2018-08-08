<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
	<div class="container">
		<a class="navbar-brand" href="{{ route('boss.home') }}">
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
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Администраторы
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="{{route('admins.create')}}"><i class="far fa-save"></i> Новый</a>
						<a class="dropdown-item" href="{{route('admins.nicks')}}"><i class="fas fa-link"></i> Связать</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="{{route('admins.index')}}"><i class="fas fa-list"></i> Все</a>
					</div>
				</li>
				<li class="nav-item"><a class="nav-link" href="{{route('admins.statistics')}}">Статистика</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Клиенты
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						{{--<a class="dropdown-item" href="{{route('services.create')}}"><i class="far fa-save"></i> Create</a>--}}
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="{{route('services.index')}}"><i class="fas fa-list"></i> Все</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						DeadLines
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="{{route('deadline.create')}}"><i class="far fa-save"></i> Create</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="{{route('deadline.index')}}"><i class="fas fa-list"></i> Все</a>
					</div>
				</li>
				<li class="nav-item"><a class="nav-link" href="{{route('logs')}}">Логи</a></li>
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
				@endguest
			</ul>
		</div>
	</div>
</nav>