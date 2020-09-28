<x-layout.app>

    <x-gastos.gastos.form-filter :selectCuentas="$selectCuentas" />

    <table class="table-auto">
        <thead class="">
            <tr>
                <th>Año</th>
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

        <tbody>
            <x-gastos.gastos.form-ingreso :selectCuentas="$selectCuentas" :selectTiposGastos="$selectTiposGastos" />

            @foreach ($movimientosMes as $movimiento)
                <x-gastos.gastos.table-item-movimiento :movimiento="$movimiento" />
            @endforeach

            <tr>
                <th>{{ request('anno') }}</th>
                <th>{{ request('mes') }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Saldo Inicial</th>
                <th></th>
                <th class="text-right">{{ fmtMonto(optional($movimientosMes->last())->saldo_inicial) }}</th>
                <th></th>
            </tr>
        </tbody>
    </table>

</x-layout.app>
