@extends('layouts.app_layout')

@section('modulo')

@include('gastos.gastos.index_form')

<table class="col-12 mt-md-3 table table-hover table-sm">
    <thead class="thead-light">
        <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Fecha</th>
            <th>Glosa</th>
            <th>Serie</th>
            <th>Tipo Gasto</th>
            <th class="text-right">Monto</th>
            <th class="text-right">Saldo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {{ Form::open([]) }}
        <tr>
            {{ Form::hidden('cuenta_id', request('cuenta_id', $selectCuentas->keys()->first())) }}
            {{ Form::hidden('anno', request('anno', $today->year)) }}
            {{ Form::hidden('mes', request('mes', $today->month)) }}
            <td></td>
            <td></td>
            <td><x-form-input name="fecha" type="date" class="form-control-sm"/></td>
            <td><x-form-input name="glosa" class="form-control-sm" /></td>
            <td><x-form-input name="serie" class="form-control-sm" /></td>
            <td><x-form-input name="tipo_gasto_id" type="select" class="custom-select-sm" :options=$selectTiposGastos /></td>
            <td><x-form-input name="monto" class="form-control-sm" /></td>
            <td><button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button></td>
            <td></td>
        </tr>
        {{ Form::close() }}

        @foreach ($movimientosMes as $movimiento)
        <tr>
            <td>{{ $movimiento->anno }}</td>
            <td>{{ $movimiento->mes }}</td>
            <td>{{ optional($movimiento->fecha)->format('d-m-Y') }}</td>
            <td>{{ $movimiento->glosa }}</td>
            <td>{{ $movimiento->serie }}</td>
            <td>{{ $movimiento->tipoGasto->tipo_gasto }}</td>
            <td class="text-right">
                {{ fmtMonto($movimiento->monto) }}
                <x-signo-movimiento :movimiento=$movimiento />
            </td>
            <td class="text-right">
                {{ fmtMonto($movimiento->saldo_final) }}
            </td>
            <td>
                {{ Form::open(['url' => route('gastos.borrarGasto', http_build_query(request()->all()))]) }}
                    {!! method_field('DELETE')!!}
                    {{ Form::hidden('id', $movimiento->getKey()) }}
                    <button type="submit" class="btn btn-sm btn-link py-md-0 by-md-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" viewBox="0 0 24 24" width="14" height="14"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
                    </button>
                {{ Form::close() }}
            </td>
        </tr>
        @endforeach

        <tr>
            <th>{{ request('anno') }}</th>
            <th>{{ request('mes') }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th>Saldo Inicial</th>
            <th></th>
            <th class="text-right">{{ fmtMonto(optional($movimientosMes->last())->saldo_inicial) }}</th>
            <th></th>
        </tr>
    </tbody>
</table>

@endsection
