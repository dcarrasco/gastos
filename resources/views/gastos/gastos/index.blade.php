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
        <tr>
            <form method="POST">
                @csrf
                <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $selectCuentas->keys()->first()) }}">
                <input type="hidden" name="anno" value="{{ request('anno', $today->year) }}">
                <input type="hidden" name="mes" value="{{ request('mes', $today->month) }}">
                <td></td>
                <td></td>
                <td><x-form-input name="fecha" type="date" class="form-control-sm"/></td>
                <td><x-form-input name="glosa" class="form-control-sm" /></td>
                <td><x-form-input name="serie" class="form-control-sm" /></td>
                <td><x-form-input name="tipo_gasto_id" type="select" class="custom-select-sm" :options=$selectTiposGastos /></td>
                <td><x-form-input name="monto" class="form-control-sm" /></td>
                <td><button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button></td>
                <td></td>
            </form>
        </tr>

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
                <form method="POST" action="{{ route('gastos.borrarGasto', http_build_query(request()->all())) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $movimiento->getKey() }}">
                    <button type="submit" class="btn btn-sm btn-link py-md-0 by-md-0">
                        <x-heroicon.delete width="14" height="14" class="mb-1"/>
                    </button>
                </form>
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
