@extends('layouts.app_layout')

@section('modulo')

@include('gastos.reporte.index_form')

@if (!$reporte->isEmpty())
    <table class="table table-hover table-sm mt-md-3">
        <thead class="thead-light">
            <th>Item</th>
            @foreach ($reporte->titulosColumnas() as $mes)
                <th class="text-center">{{ $mes }}</th>
            @endforeach
            <th class="text-center">Total</th>
            <th class="text-center">Prom</th>
        </thead>

        <tbody>
            @foreach ($reporte->titulosFilas() as $idTipoGasto => $tipoGasto)
                <tr>
                    <th scope="row">{{ $tipoGasto }}</th>

                    @foreach ($reporte->titulosColumnas() as $numMes => $mes)
                        <td class="text-center">
                            @if (! empty($reporte->getDato($idTipoGasto, $numMes, 0)))
                                <a href="{{ route('gastos.detalle', [
                                    'cuenta_id' => request('cuenta_id', $cuentas->keys()->first()),
                                    'anno' => request('anno', today()->year),
                                    'mes' => $numMes,
                                    'tipo_gasto_id' => $idTipoGasto
                                ]) }}" class="text-reset">
                                    {{ fmtMonto($reporte->getDato($idTipoGasto, $numMes)) }}
                                </a>
                            @endif
                        </td>
                    @endforeach

                    <th class="text-center table-secondary">
                        {{ fmtMonto($reporte->totalFila($idTipoGasto)) }}
                    </th>
                    <th class="text-center table-secondary">
                        {{ fmtMonto($reporte->totalFila($idTipoGasto)/$reporte->countFila($idTipoGasto)) }}
                    </th>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="table-secondary">
                <td></td>

                @foreach($reporte->titulosColumnas() as $numMes => $mes)
                    <td class="text-center font-weight-bold">
                        {{ fmtMonto($reporte->totalColumna($numMes)) }}
                    </td>
                @endforeach

                <td class="text-center font-weight-bold">
                    {{ fmtMonto($reporte->totalReporte()) }}
                </td>
                <td class="text-center font-weight-bold">
                    {{ fmtMonto($reporte->promedioReporte()) }}
                </td>
            </tr>
        </tfoot>
    </table>
@endif

@endsection
