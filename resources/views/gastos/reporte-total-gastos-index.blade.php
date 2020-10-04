<x-layout.app>

    <x-gastos.reporte.form-filter-gastos-totales />

    @if (!$reporte->isEmpty())
        <table class="table-auto text-sm">
            <thead class="bg-gray-300 border-b-2 border-gray-400">
                <tr>
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
                    <tr class="hover:bg-blue-100">
                        <th class="text-left py-2">{{ $tipoGasto }}</th>

                        @foreach ($reporte->titulosColumnas() as $numMes => $mes)
                            <td class="text-center">
                                @if (! empty($reporte->getDato($idTipoGasto, $numMes, 0)))
                                    {{ fmtMonto($reporte->getDato($idTipoGasto, $numMes)) }}
                                @endif
                            </td>
                        @endforeach

                        <td class="text-center bg-gray-300 font-bold">
                            {{ fmtMonto($reporte->totalFila($idTipoGasto)) }}
                        </td>
                        <td class="text-center bg-gray-300 font-bold">
                            {{ fmtMonto($reporte->totalFila($idTipoGasto)/$reporte->countFila($idTipoGasto)) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot class="bg-gray-300 font-bold border-t-2 border-gray-400">
                <tr>
                    <td></td>

                    @foreach($reporte->titulosColumnas() as $numMes => $mes)
                        <td class="text-center py-2">
                            {{ fmtMonto($reporte->totalColumna($numMes)) }}
                        </td>
                    @endforeach

                    <td class="text-center">
                        {{ fmtMonto($reporte->totalReporte()) }}
                    </td>
                    <td class="text-center">
                        {{ fmtMonto($reporte->promedioReporte()) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif

</x-layout.app>
