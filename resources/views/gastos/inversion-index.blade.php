<x-layout.app>
    <x-gastos.inversion.form-filter :cuentas="$cuentas" />

    @can('view-any', 'App\Models\Gastos\Gasto')
        <div x-data="{openDeleteModal: false, urlDelete: '', deleteMessage: ''}">
            <table class="table-auto text-sm w-full">
                <thead class="bg-gray-300 border-b-2 border-gray-400">
                    <tr>
                        <th class="py-2">Año</th>
                        <th>Mes</th>
                        <th>Fecha</th>
                        <th>Glosa</th>
                        <th>Tipo Movimiento</th>
                        <th class="text-right">Monto</th>
                        <th class="text-right">Saldo</th>
                        <th></th>
                        <th class="text-right">Rentab</th>
                        <th class="text-right">Rentab Año</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @php $saldo = 0; @endphp
                    @foreach ($inversion->getMovimientos() as $mov)
                        @php $saldo += $mov->valor_monto; @endphp
                        <x-gastos.inversion.table-item :inversion="$mov" :saldo="$saldo" />
                    @endforeach

                    <x-gastos.inversion.table-last-item :inversion="$inversion" />

                    @can('create', 'App\Models\Gastos\Gasto')
                        <x-gastos.inversion.form-ingreso :cuentas="$cuentas" :tiposMovimientos="$tiposMovimientos" />
                    @endcan
                </tbody>
            </table>

            <x-orm.list.modal-delete />
        </div>

        @if (! empty($datosInversion = $inversion->getJSONRentabilidadesAnual()))
            <x-gastos.inversion.chart :datosInversion="$datosInversion" />
        @endif

    @endcan
</x-layout.app>
