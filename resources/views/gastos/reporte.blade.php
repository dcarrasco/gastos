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
        <div class="offset-md-2 col-md-2"> {{ $formCuenta }} </div>
        <div class="col-md-2"> {{ $formAnno }} </div>
        <div class="col-md-2"> {{ $formTipoMovimiento }} </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </div>
</form>

<table class="table table-hover table-sm mt-md-3">
    <thead class="thead-light">
        <th></th>
        @foreach($datos['meses'] as $mes)
        <th class="text-right">{{ $mes}} </th>
        @endforeach
        <th class="text-right">Prom</th>
    </thead>
    <tbody>
        @foreach($datos['tipo_gasto_id'] as $tipo_gasto_id)
        <tr>
            <th scope="row">{{ array_get($tipoGasto, $tipo_gasto_id) }}</th>
            @foreach($datos['meses'] as $mes => $nombre)
            <td class="text-right">
                @if (array_get($datos, "datos.$tipo_gasto_id.$mes", 0) == 0)
                @else
                    $&nbsp;{{ number_format(array_get($datos, "datos.$tipo_gasto_id.$mes"), 0, ',', '.') }}
                    @endif
            </td>
            @endforeach
            <td class="text-right">
                $&nbsp;{{ number_format(array_get($datos, "sum_tipo_gasto.$tipo_gasto_id")/count($datos['sum_mes']), 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
        <tr class="table-secondary">
            <td></td>
            @foreach($datos['meses'] as $mes => $nombre)
            <td class="text-right font-weight-bold">
                $&nbsp;{{ number_format(array_get($datos, "sum_mes.$mes", 0), 0, ',', '.') }}
            </td>
            @endforeach
            <td class="text-right font-weight-bold">
                @if (count($datos['sum_mes']) > 0)
                $&nbsp;{{ number_format(collect($datos['sum_tipo_gasto'])->sum()/count($datos['sum_mes']), 0, ',', '.') }}
                @endif
            </td>
        </tr>
    </tbody>
</table>

@endsection
