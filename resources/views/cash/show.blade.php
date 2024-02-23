<x-layout.app>

    <x-cash.form-ingreso
        :cuenta="$cuenta"
        :selectCuentas="$selectCuentas"
        :tiposCargo="$tiposCargo"
        :movimiento="$movimiento"
    />

    <div class="text-xl">
        Cuenta:
        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
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
            @foreach($movimientos as $mov)
            <tr class="py-3">
                <td class="py-3 px-3">{{ $mov->fecha->format('Y-m-d') }}</td>
                <td class="py-3 px-3">{{ $mov->numero }}</td>
                <td class="py-3 px-3">{{ $mov->descripcion }}</td>
                <td class="py-3 px-3">{{ $mov->contraCuenta->nombre }}</td>
                <td class="py-3 px-3 text-center">{{ $mov->conciliado }}</td>
                <td class="py-3 px-3 text-center">{{ fmtMonto($mov->getCargo()) }}</td>
                <td class="py-3 px-3 text-center">{{ fmtMonto($mov->getIngreso()) }}</td>
                <td class="py-3 px-3 text-center">{{ fmtMonto($mov->balance) }}</td>
                <td>
                    <a href="{{ route('cash.showMovimiento', ['cuenta' => $cuenta, 'movimiento' => $mov]) }}">editar</a>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
    @endcan

</x-layout.app>
