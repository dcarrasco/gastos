@props(['modulos', 'menuModulo' => [], ])

<!-- ============================== MENU MODULO ============================== -->
<ul>
@foreach($modulos as $modulo)
    <li class="px-6 py-3 text-white text-decoration-none">
        <a href="{{ route($modulo->url) }}" class="hover:text-gray-500 {{$modulo->selected ? 'font-semibold' : ''}}">
            <span class="fa fa-{{ $modulo->icono }} fa-fw"></span>
            {{ $modulo->modulo }}
        </a>
        @if (isset($menuModulo) and $modulo->selected)
            <ul class="pt-3 text-sm">
            @foreach ($menuModulo as $subModulo)
                <li class="px-4 py-1">
                    <a href="{{ $subModulo->url }}" class="hover:text-gray-500 {{$subModulo->selected ? 'font-semibold' : ''}}">
                        {{ $subModulo->nombre }}
                    </a>
                </li>
            @endforeach
            </ul>
        @endif
    </li>
@endforeach
</ul>
<!-- ============================== /MENU MODULO ============================== -->
