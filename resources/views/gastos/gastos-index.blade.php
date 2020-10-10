<x-layout.app>

    <x-gastos.gastos.form-filter :selectCuentas="$selectCuentas" />

    <table class="table-auto text-sm w-full">
        <thead class="bg-gray-300 border-b-2 border-gray-400">
            <tr>
                <th class="py-2">Año</th>
                <th>Mes</th>
                <th>Fecha</th>
                <th>Glosa</th>
                <th>Serie</th>
                <th>Tipo Gasto</th>
                <th class="text-right">Monto</th>
                <th class="text-right">Saldo</th>
                <th></th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            <x-gastos.gastos.form-ingreso :selectCuentas="$selectCuentas" :selectTiposGastos="$selectTiposGastos" />

            @foreach ($movimientosMes as $movimiento)
                <x-gastos.gastos.table-item-movimiento :movimiento="$movimiento" />
            @endforeach

            <tr class="font-bold hover:bg-blue-100">
                <td class="py-2">{{ request('anno') }}</td>
                <td>{{ request('mes') }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="py-2">Saldo Inicial</td>
                <td></td>
                <td class="text-right">{{ fmtMonto(optional($movimientosMes->last())->saldo_inicial) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

</x-layout.app>
