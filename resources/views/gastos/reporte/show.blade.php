<x-layout.app>

    <table class="offset-md-1 col-md-10 mt-md-3 table table-hover table-sm">
        @foreach ($movimientosMes as $mov)
            @if ($loop->first)
                <thead class="thead-light">
                    <tr>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>Fecha</th>
                        <th>Glosa</th>
                        <th>Serie</th>
                        <th>Tipo Gasto</th>
                        <th class="text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
            @endif

            <tr>
                <td>{{ $mov->anno }}</td>
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
                <tfoot>
                    <tr class="thead-light">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th class="text-right">
                            {{ fmtMonto($movimientosMes->sum('monto')) }}
                        </th>
                    </tr>
                </tfoot>
            @endif

        @endforeach
    </table>

</x-layout.app>
