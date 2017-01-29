@if (isset($modelList))
<div class="row">
	<!-- ============================== MENU MODULO ============================== -->
	<div class="col-md-2">
	<ul class="nav nav-stacked nav-sidebar hidden-print">
		@foreach ($modelList as $model => $modelProps)
		<li class="{{ $model === $modelSelected ? 'active' : '' }}">
			<a href="{{ route($routeName.'.index', [$model]) }}">
				<span class="fa fa-{{ $modelProps['icono'] }} fa-fw"></span> {{ $modelProps['nombre'] }}
			</a>
			</li>
		@endforeach
	</ul>
	</div>
	<!-- ============================== /MENU MODULO ============================== -->
	<div class="col-md-10">
@endif