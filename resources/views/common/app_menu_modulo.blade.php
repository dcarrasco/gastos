@if (isset($menuModulo))
<div class="row mh-100" style="min-height: 100vh;">
	<!-- ============================== MENU MODULO ============================== -->
	<div class="col-2 bg-secondary px-0">
		<ul class="list-group list-group-flush hidden-print">
		@foreach ($menuModulo as $modulo )
			<a href="{{ $modulo['url'] }}" class="list-group-item list-group-item-action {{ $modulo['resource'] === $moduloSelected ? 'active' : '' }}">
				<span class="fa fa-{{ $modulo['icono'] }} fa-fw"></span>
				{!! $modulo['nombre'] !!}
			</a>
		@endforeach
		</ul>
	</div>
	<!-- ============================== /MENU MODULO ============================== -->

	<div class="col-10">
@endif
