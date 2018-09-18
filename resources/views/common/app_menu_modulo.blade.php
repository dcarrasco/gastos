@if (isset($menuModulo))
<div class="row">
	<!-- ============================== MENU MODULO ============================== -->
	<div class="col-md-2 h-100">
	<ul class="list-group hidden-print">
		@foreach ($menuModulo as $modulo )
		<li class="list-group-item {{ $modulo['resource'] === $moduloSelected ? 'active' : '' }}">
			<a href="{{ $modulo['url'] }}">
				<span class="fa fa-{{ $modulo['icono'] }} fa-fw"></span>
				{!! $modulo['nombre'] !!}
			</a>
		</li>
		@endforeach
	</ul>
	</div>
	<!-- ============================== /MENU MODULO ============================== -->
	<div class="col-md-10">
@endif
