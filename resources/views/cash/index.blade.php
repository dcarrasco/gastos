<x-layout.app>

    @can('view-any', 'App\Models\Cash\Cuenta')
        <table class="table-auto text-sm w-full">
            <thead class="{{ themeColor('thead_bg') }} border-b border-gray-400 uppercase text-xs text-gray-600 font-normal">
                <tr>
                    <th class="py-2">Nivel</th>
                    <th class="text-left">Codigo</th>
                    <th class="text-left">Nombre</th>
                    <th class="text-left">Descripcion</th>
                    <th class="text-center">Saldo</th>
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
                            <td class="py-3 px-3 text-right">{{ fmtMonto($cuenta->saldo) }}</td>
                            <td class="py-3 px-3 text-center">
                                @if (! $cuenta->contenedor)
                                    <a href="{{ route('cash.show', $cuenta) }}">Ingresar</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endcan
            </tbody>

        </table>
    @endcan

</x-layout.app>
