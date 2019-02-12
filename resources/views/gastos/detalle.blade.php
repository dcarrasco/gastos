@extends('common.app_layout')

@section('modulo')

<table class="offset-md-1 col-md-10 mt-md-3 table table-hover table-sm">
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
    @foreach ($movimientosMes as $mov)
        <tr>
            <td>{{ $mov->anno }}</td>
            <td>{{ $mov->mes }}</td>
            <td>{{ optional($mov->fecha)->format('d-m-Y') }}</td>
            <td>{{ $mov->glosa }}</td>
            <td>{{ $mov->serie }}</td>
            <td>{{ $mov->tipoGasto->tipo_gasto }}</td>
            <td class="text-right">
                $&nbsp;{{ number_format($mov->monto, 0, ',', '.') }}
                @if ($mov->tipoMovimiento->signo == -1)
                    <small><span class="fa fa-minus-circle text-danger"></span></small>
                @else
                    <small><span class="fa fa-plus-circle text-success"></span></small>
                @endif
            </td>
        </tr>
    @endforeach
    <tr class="thead-light">
        <th></th>
        <th></th>
        <th></th>
        <th>TOTAL</th>
        <th></th>
        <th></th>
        <th class="text-right">
            $&nbsp;{{ number_format($movimientosMes->sum('monto'), 0, ',', '.') }}
        </th>
    </tr>
    </tbody>
</table>

@endsection
