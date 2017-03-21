<!-- ============================== NAVBAR ============================== -->
<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
	<div class="container-fluid">

		<div class="navbar-header">
			<a class="navbar-brand" href="#">{!! auth()->user()->moduloAppName() !!} </a>
			<button class="navbar-toggle" data-toggle="collapse" data-target=".navMenuCollapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div> <!-- DIV class="navbar-header" -->

		<div class="collapse navbar-collapse navMenuCollapse">
			<ul class="nav navbar-nav navbar-right">
			@foreach(auth()->user()->getMenuApp() as $menuApp)
				<li class="dropdown {{ $menuApp['selected'] ? 'active' : '' }}">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<span class="fa fa-{{ $menuApp['icono'] }} fa-fw"></span> {{ $menuApp['app'] }} <b class="caret"></b>
					</a>

					<ul class="dropdown-menu">
						@foreach($menuApp['modulos'] as $modulo)
						<li class="{{ $modulo['selected'] ? 'active' : '' }}">
							<a href="{{ route($modulo['url']) }}"><span class="fa fa-{{ $modulo['icono'] }} fa-fw"></span> {{ $modulo['modulo'] }}</a>
						</li>
						@endforeach
					</ul>
				</li>
			@endforeach
			<li>
				<a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
					<span class="fa fa-power-off fa-fw"></span> Logout {{ auth()->user()->getFirstName() }}
				</a>
				{{ Form::open(['url' => route('logout'), 'id' => 'logout-form']) }}{{ Form::close() }}
			</li>

			</ul>
		</div> <!-- DIV class="collapse navbar-collapse navMenuCollapse" -->

	</div> <!-- DIV class="container" -->
</nav>
<!-- ============================== /NAVBAR ============================== -->
