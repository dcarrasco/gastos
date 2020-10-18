<x-layout.app>

    <x-gastos.gastos.form-filter :selectCuentas="$selectCuentas" />

    @can('view-any', 'App\Models\Gastos\Gasto')
    <div x-data="{openDeleteModal: false, urlDelete: '', deleteMessage: ''}">
    <table class="table-auto text-sm w-full">
        <thead class="bg-gray-100 border-b border-gray-400 uppercase text-xs font-normal">
            <tr>
                <th class="py-2">Fecha</th>
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

            <tr class="hover:bg-blue-100">
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
    <x-orm.list.modal-delete />
    </div>
    @endcan

</x-layout.app>
