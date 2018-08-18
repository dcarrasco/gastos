@if (isset($menuModulo))
<div class="row">
	<!-- ============================== MENU MODULO ============================== -->
	<div class="col-md-2">
	<ul class="nav nav-stacked nav-sidebar hidden-print">
		@foreach ($menuModulo as $modulo => $moduloProps)
		<li class="{{ $modulo === $moduloSelected ? 'active' : '' }}">
			<a href="{{ $moduloProps['url'] }}">
				<span class="fa fa-{{ $moduloProps['icono'] }} fa-fw"></span> {{ $moduloProps['nombre'] }}
			</a>
			</li>
		@endforeach
	</ul>
	</div>
	<!-- ============================== /MENU MODULO ============================== -->
	<div class="col-md-10">
@endif
