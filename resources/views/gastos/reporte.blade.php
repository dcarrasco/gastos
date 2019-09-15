@extends('common.app_layout')

@section('modulo')
<form>
    <div class="form-row">
        <div class="offset-md-2 col-md-2">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">Año</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">Tipo Movimiento</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-2 col-md-2">
            {{ Form::select('cuenta_id', $cuenta->selectCuentasGastos(), request('cuenta_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::select('anno', $cuenta->selectAnnos(), request('anno'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::select('tipo_movimiento_id', $formTipoMovimiento, request('tipo_movimiento_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </div>
</form>

<table class="table table-hover table-sm mt-md-3">
    <thead class="thead-light">
        <th>Item</th>
        @foreach($reporte->titulosColumnas() as $mes)
        <th class="text-center">{{ $mes }}</th>
        @endforeach
        <th class="text-center">Total</th>
        <th class="text-center">Prom</th>
    </thead>
    <tbody>
        @foreach($reporte->titulosFilas() as $idTipoGasto => $tipoGasto)
        <tr>
            <th scope="row">{{ $tipoGasto }}</th>
            @foreach($reporte->titulosColumnas() as $numMes => $mes)
            <td class="text-right">
                @if ($reporte->getDato($idTipoGasto, $numMes, 0) == 0)
                @else
                    <a href="{{ route('gastos.detalle', [
                        'cuenta_id' => $cuentaId,
                        'anno' => $anno,
                        'mes' => $numMes,
                        'tipo_gasto_id' => $idTipoGasto,
                    ]) }}" class="text-reset">
                        $&nbsp;{{ number_format($reporte->getDato($idTipoGasto, $numMes), 0, ',', '.') }}
                    </a>
                @endif
            </td>
            @endforeach
            <th class="text-right table-secondary">
                $&nbsp;{{ number_format($reporte->totalFila($idTipoGasto), 0, ',', '.') }}
            </th>
            <th class="text-right table-secondary">
                $&nbsp;{{ number_format($reporte->totalFila($idTipoGasto)/$reporte->countFila($idTipoGasto), 0, ',', '.') }}
            </th>
        </tr>
        @endforeach
        <tr class="table-secondary">
            <td></td>
            @foreach($reporte->titulosColumnas() as $numMes => $mes)
            <td class="text-right font-weight-bold">
                $&nbsp;{{ number_format($reporte->totalColumna($numMes), 0, ',', '.') }}
            </td>
            @endforeach
            <td class="text-right font-weight-bold">
                $&nbsp;{{ number_format($reporte->totalReporte(), 0, ',', '.') }}
            </td>
            <td class="text-right font-weight-bold">
                @if ($reporte->getReporte()->count() > 0)
                $&nbsp;{{ number_format($reporte->promedioReporte(), 0, ',', '.') }}
                @endif
            </td>
        </tr>
    </tbody>
</table>

@endsection
