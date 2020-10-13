<x-layout.app>
    <x-gastos.inversion.form-filter :cuentas="$cuentas" />

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
            <?php $saldo = 0; ?>
            @foreach ($inversion->getMovimientos() as $mov)
                <tr class="hover:bg-blue-100">
                    <td class="text-center py-2">{{ $mov->anno }}</td>
                    <td class="text-center">{{ $mov->mes }}</td>
                    <td class="text-center">{{ optional($mov->fecha)->format('d-m-Y') }}</td>
                    <td>{{ $mov->glosa }}</td>
                    <td class="text-center">{{ $mov->tipoMovimiento->tipo_movimiento }}</td>
                    <td class="text-right">
                        {{ fmtMonto($mov->monto) }}
                        <x-signo-movimiento :movimiento=$mov />
                    </td>
                    <td class="text-right">{{ fmtMonto($saldo += $mov->valor_monto) }}</td>
                    <td>
                        @can('delete', $mov)
                        <form method="POST" action="{{ route('gastos.borrarGasto', http_build_query(request()->all())) }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $mov->getKey() }}">
                            <button type="submit" class="p-0 text-gray-600">
                                <x-heroicon.delete width="14" height="14"/>
                            </button>
                        </form>
                        @endcan
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                @if ($loop->last)
                    @if($inversion->saldoFinal())
                        <tr class="hover:bg-blue-100 border-t-2">
                            <td class="text-center py-2">{{ optional($inversion->saldoFinal())->anno }}</td>
                            <td class="text-center">{{ optional($inversion->saldoFinal())->mes }}</td>
                            <td class="text-center">{{ optional(optional($inversion->saldoFinal())->fecha)->format('d-m-Y') }}</td>
                            <td>{{ optional($inversion->saldoFinal())->glosa }}</td>
                            <td class="text-center">
                                {{ optional(optional($inversion->saldoFinal())->tipoMovimiento)->tipo_movimiento }}
                            </td>
                            <td></td>
                            <td class="text-right">{{ fmtMonto(optional($inversion->saldoFinal())->monto) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="bg-gray-300 font-bold border-t-2 border-gray-400">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right py-2">Utilidad</td>
                            <td class="text-right">
                                {{ fmtMonto($inversion->util($inversion->saldoFinal())) }}
                            </td>
                            <td></td>
                            <td class="text-center">
                                {{ fmtPorcentaje(100*$inversion->rentabilidad($inversion->saldoFinal())) }}
                            </td>
                            <td class="text-center">
                                {{ fmtPorcentaje(100*$inversion->rentabilidadAnual($inversion->saldoFinal())) }}
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach

            @can('create', 'App\Models\Gastos\Gasto')
            <tr>
                <form method="POST">
                    @csrf
                    <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $cuentas->keys()->first()) }}">
                    <input type="hidden" name="anno" value="{{ request('anno', today()->year) }}">
                    <td></td>
                    <td></td>
                    <td class="py-2"><x-form-input name="fecha" type="date" class="" /></td>
                    <td><x-form-input name="glosa" class="w-32" /></td>
                    <td><x-form-input name="tipo_movimiento_id" type="select" class="" :options=$tiposMovimientos /></td>
                    <td><x-form-input name="monto" class="w-32" /></td>
                    <td>
                        <x-button type="submit">
                            Ingresar
                        </x-button>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </form>
            </tr>
            @endcan
        </tbody>
    </table>

    @if (! empty($datosInversion = $inversion->getJSONRentabilidadesAnual()))
        <x-gastos.inversion.chart :datosInversion="$datosInversion" />
    @endif
</x-layout.app>
