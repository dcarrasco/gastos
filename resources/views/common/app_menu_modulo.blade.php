@if (isset($menuModulo))
<div class="row">
	<!-- ============================== MENU MODULO ============================== -->
	<div class="col-md-2 h-100">
	<ul class="list-group hidden-print">
		@foreach ($menuModulo as $modulo => $moduloProps)
		<li class="list-group-item {{ $modulo === $moduloSelected ? 'active' : '' }}">
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
