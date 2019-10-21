@extends('common.app_layout')

@section('modulo')
<form>
    <div class="form-row">
        <div class="col-3">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-2">
            <label class="col-form-label">Año</label>
        </div>
        <div class="col-3">
            <label class="col-form-label">Mes</label>
        </div>
    </div>

    <div class="form-row">
        <div class="col-3">
            {{ Form::select('cuenta_id', $selectCuentas, request('cuenta_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-2">
            {{ Form::selectYear('anno', $today->year, 2015, request('anno', $today->year), ['class' => 'form-control']) }}
        </div>
        <div class="col-3">
            {{ Form::selectMonth('mes', request('mes', $today->month), ['class' => 'form-control']) }}
         </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary">Consultar</button>
            <button name="recalcula" value="recalcula" class="btn btn-secondary pull-right">Recalcula saldos</button>
        </div>
    </div>
</form>

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
            <td>
                <input type="date" name="fecha" value="{{ old('fecha') }}" autocomplete="off" class="form-control form-control-sm @error('fecha') is-invalid @enderror">
            </td>
            <td>
                <input type="text" name="glosa" value="{{ old('glosa') }}" autocomplete="off" class="form-control form-control-sm @error('glosa') is-invalid @enderror">
            </td>
            <td>
                <input type="text" name="serie" value="{{ old('serie') }}" autocomplete="off" class="form-control form-control-sm @error('serie') is-invalid @enderror">
            </td>
            <td>
                {{ Form::select('tipo_gasto_id', $selectTiposGastos, request('tipo_gasto_id'), ['class' => 'form-control form-control-sm']) }}
            </td>
            <td>
                <input type="text" name="monto" autocomplete="off" class="form-control form-control-sm {{ $errors->has('monto') ? 'is-invalid' : '' }}">
            </td>
            <td>
                <button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button>
            </td>
            <td></td>
        </tr>
        {{ Form::close() }}

        @foreach ($movimientosMes as $movimiento)
        <tr>
            <td>{{ $movimiento['movimiento']->anno }}</td>
            <td>{{ $movimiento['movimiento']->mes }}</td>
            <td>{{ optional($movimiento['movimiento']->fecha)->format('d-m-Y') }}</td>
            <td>{{ $movimiento['movimiento']->glosa }}</td>
            <td>{{ $movimiento['movimiento']->serie }}</td>
            <td>{{ $movimiento['movimiento']->tipoGasto->tipo_gasto }}</td>
            <td class="text-right">
                $&nbsp;{{ number_format($movimiento['movimiento']->monto, 0, ',', '.') }}
                @if ($movimiento['movimiento']->tipoMovimiento->signo == -1)
                    <small><span class="fa fa-minus-circle text-danger"></span></small>
                @else
                    <small><span class="fa fa-plus-circle text-success"></span></small>
                @endif
            </td>
            <td class="text-right">
                $&nbsp;{{ number_format($movimiento['saldoInicial'] + $movimiento['movimiento']->valor_monto, 0, ',', '.') }}
            </td>
            <td>
                {{ Form::open(['url' => route('gastos.borrarGasto', http_build_query(request()->all()))]) }}
                    {!! method_field('DELETE')!!}
                    {{ Form::hidden('id', $movimiento['movimiento']->getKey()) }}
                    <button type="submit" class="btn btn-sm btn-link py-md-0 by-md-0">
                        <span class="fa fa-trash text-muted"></span>
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
            <th class="text-right">$&nbsp;{{ number_format($movimientosMes->last()['saldoInicial'], 0, ',', '.') }}</th>
            <th></th>
        </tr>
    </tbody>
</table>

@endsection
