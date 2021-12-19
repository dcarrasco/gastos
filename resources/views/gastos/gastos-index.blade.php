<x-layout.app>

    <x-gastos.gastos.form-filter :selectCuentas="$selectCuentas" />

    @can('view-any', 'App\Models\Gastos\Gasto')
        <div
            x-data="{openDeleteModal: false, urlDelete: '', deleteMessage: ''}"
            class="py-2 rounded-lg {{ themeColor('thead_bg') }} border shadow-sm"
        >
            <table class="table-auto text-sm w-full">
                <thead class="{{ themeColor('thead_bg') }} border-b border-gray-400 uppercase text-xs font-normal">
                    <tr>
                        <th class="py-2">Fecha</th>
                        <th>Glosa</th>
                        <th>Serie</th>
                        <th>Tipo Gasto</th>
                        <th>Monto</th>
                        <th>Saldo</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @can('create', 'App\Models\Gastos\Gasto')
                        <x-gastos.gastos.form-ingreso
                            :selectCuentas="$selectCuentas"
                            :selectTiposGastos="$selectTiposGastos"
                        />
                    @endcan

                    @foreach ($movimientosMes as $movimiento)
                        <x-gastos.gastos.table-item :movimiento="$movimiento" />
                    @endforeach

                    <tr class="{{ themeColor('thead_bg') }}">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="py-2">Saldo Inicial</td>
                        <td></td>
                        <td class="text-right px-2">{{ fmtMonto(optional($movimientosMes->last())->saldo_inicial) }}</td>
                    </tr>
                </tbody>
            </table>

            <x-orm.list.modal-delete />

        </div>
    @endcan

</x-layout.app>
