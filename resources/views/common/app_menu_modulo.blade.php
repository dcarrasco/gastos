<!-- ============================== MENU MODULO ============================== -->
<ul class="list-group list-group-flush hidden-print">
@foreach(auth()->user()->getMenuApp() as $menuApp)
@foreach($menuApp['modulos'] as $modulo)
    <a href="{{ route($modulo['url']) }}" class="list-group-item list-group-item-action {{ $modulo['selected'] ? 'active' : ''}}">
        <span class="fa fa-{{ $modulo['icono'] }} fa-fw"></span>
        {{ $modulo['modulo'] }}
    </a>
@endforeach
@endforeach
</ul>
<!-- ============================== /MENU MODULO ============================== -->
