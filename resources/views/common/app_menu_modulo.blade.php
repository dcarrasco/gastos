<!-- ============================== MENU MODULO ============================== -->
@foreach(auth()->user()->getMenuApp() as $menuApp)
@foreach($menuApp['modulos'] as $modulo)
<div class="px-4 py-2">
    <a href="{{ route($modulo['url']) }}" class="text-white text-decoration-none font-weight-bold">
        <span class="fa fa-{{ $modulo['icono'] }} fa-fw"></span>
        {{ $modulo['modulo'] }}
    </a>

    @if (isset($menuModulo) and $modulo['selected'])
    @foreach ($menuModulo as $modulo )
    <div class="pl-4 py-1">
        <a href="{{ $modulo['url'] }}" class="text-white text-decoration-none">
            {{ $modulo['nombre'] }}
        </a>
    </div>
    @endforeach
    @endif
</div>
@endforeach
@endforeach
<!-- ============================== /MENU MODULO ============================== -->
