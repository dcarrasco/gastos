<x-layout.app>

    <div class="grid grid-cols-2">
        <div class="text-xl">
            Cuenta:
            {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
        </div>

        @can('create', 'App\Models\Cash\Movimiento')
        <div class="flex justify-end py-3">
            <x-button type="link" link="{{ route('cashMovimientos.create', ['cuenta' => $cuenta]) }}">Ingresar</x-button>
        </div>
        @endcan
    </div>

    @can('view-any', 'App\Models\Cash\Movimiento')
    <table class="table-auto text-sm w-full">
        <thead class="{{ themeColor('thead_bg') }} border-b border-gray-400 uppercase text-xs text-gray-600 font-normal">
            <tr>
                <th class="py-2">Fecha</th>
                <th>Numero</th>
                <th>Descripcion</th>
                <th>Cuenta Contrapartida</th>
                <th>Conciliado</th>
                <th>{{ $cuenta->tipoCuenta->nombre_cargo }}</th>
                <th>{{ $cuenta->tipoCuenta->nombre_abono }}</th>
                <th>Saldo</th>
                <th></th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($movimientos as $movimiento)
            <tr class="py-3">
                <td class="py-3 px-3">{{ $movimiento->fecha->format('Y-m-d') }}</td>
                <td class="py-3 px-3">{{ $movimiento->numero }}</td>
                <td class="py-3 px-3">{{ $movimiento->descripcion }}</td>
                <td class="py-3 px-3">{{ $movimiento->contraCuenta->nombre }}</td>
                <td class="py-3 px-3 text-center">{{ $movimiento->conciliado }}</td>
                <td class="py-3 px-3 text-center">
                    {{ $movimiento->getCargo() != 0 ? fmtMonto($movimiento->getCargo()) : "" }}
                </td>
                <td class="py-3 px-3 text-center">
                    {{ $movimiento->getIngreso() != 0 ? fmtMonto($movimiento->getIngreso()) : "" }}
                </td>
                <td class="py-3 px-3 text-center">{{ fmtMonto($movimiento->balance) }}</td>
                <td>
                    <a
                        class="hover:text-blue-500 hover:underline"
                        href="{{ route('cashMovimientos.show', compact('cuenta', 'movimiento')) }}"
                    >
                        editar
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
    @endcan

</x-layout.app>
