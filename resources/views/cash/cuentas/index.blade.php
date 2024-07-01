<x-layout.app>

    @can('view-any', 'App\Models\Cash\Cuenta')
    @php
        $maxLevel = $cuentas->pluck('level')->max()
    @endphp

    <table class="table-auto text-sm w-full">
        <thead class="{{ themeColor('thead_bg') }} border-b border-gray-400 uppercase text-xs text-gray-600 font-normal">
            <tr>
                <th class="py-2">Nivel</th>
                <th class="text-left">Codigo</th>
                <th class="text-left">Nombre</th>
                <th class="text-left">Descripcion</th>
                @foreach(range($maxLevel, 1) as $level)
                    <th class="text-center">Saldo N{{ $level }}</th>
                @endforeach
                <th></th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            @can('create', 'App\Models\Cash\Cuenta')
            @foreach($cuentas as $cuenta)
            <tr>
                <td class="py-3 px-3">{{ $cuenta->level }}</td>
                <td class="py-3 px-3">{{ $cuenta->codigo }}</td>
                <td class="py-3 px-3">
                    {!! Str::repeat("&nbsp;", ($cuenta->level - 1) * 5); !!}
                    {{ $cuenta->nombre }}
                </td>
                <td class="py-3 px-3">{{ $cuenta->descripcion }}</td>

                @foreach(range($maxLevel, 1) as $level)
                    @if($level == $cuenta->level)
                    <td class="py-3 px-3 text-center">{{ fmtMonto($cuenta->saldo) }}</td>
                    @else
                    <td></td>
                    @endif
                @endforeach

                <td class="py-3 px-3 text-center">
                    @if (! $cuenta->contenedor)
                    <a class="hover:text-blue-500 hover:underline" href="{{ route('cashMovimientos.index', $cuenta) }}">Ingresar</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endcan
        </tbody>

    </table>
    @endcan

</x-layout.app>
