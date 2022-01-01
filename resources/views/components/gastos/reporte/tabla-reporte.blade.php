@props(['reporte', 'cuentas'])

@if (!$reporte->isEmpty())
    <div class="{{ themeColor('thead_bg') }} py-2 rounded-lg border shadow-sm">
        <table class="text-sm w-full">
            <thead class="{{ themeColor('thead_bg') }} border-b-2 border-gray-400 text-xs uppercase text-gray-600 text-normal">
                <tr class="">
                    <th class="py-2">Item</th>
                    @foreach ($reporte->titulosColumnas() as $mes)
                        <th class="text-center">{{ $mes }}</th>
                    @endforeach
                    <th class="text-center">Total</th>
                    <th class="text-center">Prom</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($reporte->titulosFilas() as $idTipoGasto => $tipoGasto)
                    <tr class="hover:{{ themeColor('tr_hover') }}">
                        <th class="text-left py-2">{{ $tipoGasto }}</th>

                        @foreach ($reporte->titulosColumnas() as $numMes => $mes)
                            <td class="text-center">
                                @if (! empty($reporte->getDato($idTipoGasto, $numMes, 0)))
                                    <a
                                        href="{{ route('gastos.detalle', [
                                                'cuenta_id' => request('cuenta_id', $cuentas->keys()->first()),
                                                'anno' => request('anno', today()->year),
                                                'mes' => $numMes,
                                                'tipo_gasto_id' => $idTipoGasto
                                            ]) }}"
                                        class="text-reset hover:text-blue-500"
                                    >
                                        {{ $reporte->formattedDato($idTipoGasto, $numMes) }}
                                    </a>
                                @endif
                            </td>
                        @endforeach

                        <td class="text-center bg-gray-300 font-bold">
                            {{ $reporte->formattedTotalFila($idTipoGasto) }}
                        </td>
                        <td class="text-center bg-gray-300 font-bold">
                            {{ $reporte->formattedPromedioFila($idTipoGasto) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot class="bg-gray-300 font-bold border-t-2 border-gray-400">
                <tr>
                    <td></td>

                    @foreach($reporte->titulosColumnas() as $numMes => $mes)
                        <td class="text-center py-2">
                            {{ $reporte->formattedTotalColumna($numMes) }}
                        </td>
                    @endforeach

                    <td class="text-center">
                        {{ $reporte->formattedTotalReporte() }}
                    </td>
                    <td class="text-center">
                        {{ $reporte->formattedPromedioReporte() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endif
