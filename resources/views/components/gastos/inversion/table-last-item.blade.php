@props(['inversion'])

@if($inversion->saldoFinal())
    <tr class="hover:{{ themeColor('tr_hover') }} border-t-2">
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

    <tr class="{{ themeColor('thead_bg') }} font-bold border-t-2 border-gray-400">
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
            {{ $inversion->fmtRentabilidad($inversion->saldoFinal()) }}
        </td>
        <td class="text-center">
            {{ $inversion->fmtRentabilidadAnual($inversion->saldoFinal()) }}
        </td>
    </tr>
@endif
