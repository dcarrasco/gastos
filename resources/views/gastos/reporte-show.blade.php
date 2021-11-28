<x-layout.app>

    <table class="table-auto text-sm w-full">
        @foreach ($movimientosMes as $mov)
            @if ($loop->first)
                <thead class="{{ themeColor('thead_bg') }} border-b-2 border-gray-400">
                    <tr>
                        <th class="py-2">Año</th>
                        <th>Mes</th>
                        <th>Fecha</th>
                        <th>Glosa</th>
                        <th>Serie</th>
                        <th>Tipo Gasto</th>
                        <th class="text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
            @endif

            <tr class="hover:{{ themeColor('tr_hover') }}">
                <td class="py-2">{{ $mov->anno }}</td>
                <td>{{ $mov->mes }}</td>
                <td>{{ optional($mov->fecha)->format('d-m-Y') }}</td>
                <td>{{ $mov->glosa }}</td>
                <td>{{ $mov->serie }}</td>
                <td>{{ $mov->tipoGasto->tipo_gasto }}</td>
                <td class="text-right">
                    {{ fmtMonto($mov->monto) }}
                    <x-signo-movimiento :movimiento=$mov />
                </td>
            </tr>

            @if ($loop->last)
                </tbody>
                <tfoot class="bg-gray-300 font-bold border-t-2 border-gray-400">
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="py-2">TOTAL</td>
                        <td></td>
                        <td></td>
                        <td class="text-right">
                            {{ fmtMonto($movimientosMes->sum('monto')) }}
                        </td>
                    </tr>
                </tfoot>
            @endif

        @endforeach
    </table>

</x-layout.app>
