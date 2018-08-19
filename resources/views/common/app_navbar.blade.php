<!-- ============================== NAVBAR ============================== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation">
	<a class="navbar-brand" href="#">{!! auth()->user()->moduloAppName() !!} </a>
	<button class="navbar-toggler" data-toggle="collapse" data-target="#navMenuCollapse">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navMenuCollapse">
		<ul class="navbar-nav ml-auto">
			@foreach(auth()->user()->getMenuApp() as $menuApp)
			<li class="nav-item dropdown {{ $menuApp['selected'] ? 'active' : '' }}">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
					<span class="fa fa-{{ $menuApp['icono'] }} fa-fw"></span> {{ $menuApp['app'] }} <b class="caret"></b>
				</a>

				<div class="dropdown-menu">
					@foreach($menuApp['modulos'] as $modulo)
					<a class="dropdown-item" href="{{ route($modulo['url']) }}">
						<span class="fa fa-{{ $modulo['icono'] }} fa-fw"></span>
						{{ $modulo['modulo'] }}
					</a>
					@endforeach
				</div>
			</li>
			@endforeach
			<li class="nav-item">
				<a class="nav-link" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
					<span class="fa fa-power-off fa-fw"></span> Logout {{ auth()->user()->getFirstName() }}
				</a>
				{{ Form::open(['url' => route('logout'), 'id' => 'logout-form']) }}{{ Form::close() }}
			</li>

		</ul>
	</div> <!-- DIV class="collapse navbar-collapse navMenuCollapse" -->
</nav>
<!-- ============================== /NAVBAR ============================== -->
