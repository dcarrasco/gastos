<x-layout.app>
    <x-gastos.inversion.form-filter :cuentas="$cuentas" />

    <table class="table-auto text-sm">
        <thead class="bg-gray-300 border-b-2 border-gray-400">
            <tr>
                <th>Año</th>
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
                        <form method="POST" action="{{ route('gastos.borrarGasto', http_build_query(request()->all())) }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $mov->getKey() }}">
                            <button type="submit" class="btn btn-sm btn-link p-0 align-top">
                                <x-heroicon.delete width="14" height="14"/>
                            </button>
                        </form>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                @if ($loop->last)
                    @if($inversion->saldoFinal())
                        <tr class="bg-gray-300 font-bold border-t-2 border-gray-400">
                            <td class="text-center">{{ optional($inversion->saldoFinal())->anno }}</td>
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
                            <td class="text-right">Utilidad</td>
                            <td class="text-right">
                                {{ fmtMonto($inversion->util($inversion->saldoFinal())) }}
                            </td>
                            <td></td>
                            <td class="text-right">
                                {{ fmtPorcentaje(100*$inversion->rentabilidad($inversion->saldoFinal())) }}
                            </td>
                            <td class="text-right">
                                {{ fmtPorcentaje(100*$inversion->rentabilidadAnual($inversion->saldoFinal())) }}
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach

            <tr>
                <form method="POST">
                    @csrf
                    <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $cuentas->keys()->first()) }}">
                    <input type="hidden" name="anno" value="{{ request('anno', today()->year) }}">
                    <td></td>
                    <td></td>
                    <td><x-form-input name="fecha" type="date" class="" /></td>
                    <td><x-form-input name="glosa" class="w-32" /></td>
                    <td><x-form-input name="tipo_movimiento_id" type="select" class="" :options=$tiposMovimientos /></td>
                    <td><x-form-input name="monto" class="w-32" /></td>
                    <td>
                        <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-md outline-none">Ingresar</button>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </form>
            </tr>
        </tbody>
    </table>

    @if (! empty($datosInversion = $inversion->getJSONRentabilidadesAnual()))
        <x-gastos.inversion.chart :datosInversion="$datosInversion" />
    @endif
</x-layout.app>
