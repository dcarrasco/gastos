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
            <label class="col-form-label">Mes</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-2 col-md-2"> {{ $formCuenta }} </div>
        <div class="col-md-2"> {{ $formAnno }} </div>
        <div class="col-md-2"> {{ $formMes }} </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </div>
</form>

@if (count($movimientosMes) > 0)
{{ Form::open([]) }}
<table class="offset-md-2 col-md-8 mt-md-3 table">
    <thead class="thead-light">
        <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Fecha</th>
            <th>Tipo Gasto</th>
            <th>Tipo Movimiento</th>
            <th>Signo</th>
            <th>Monto</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($movimientosMes as $mov)
        <tr>
            <td>{{ $mov->anno }}</td>
            <td>{{ $mov->mes }}</td>
            <td>{{ $mov->fecha }}</td>
            <td>{{ $mov->tipoGasto->tipo_gasto }}</td>
            <td>{{ $mov->tipoMovimiento->tipo_movimiento }}</td>
            <td>{{ $mov->tipoMovimiento->signo }}</td>
            <td>{{ $mov->monto }}</td>
            <td>{{ $mov->monto }}</td>
        </tr>
    @endforeach
        <tr>
            {{ Form::hidden('cuenta_id', request()->input('cuenta_id')) }}
            {{ Form::hidden('anno', request()->input('anno')) }}
            {{ Form::hidden('mes', request()->input('mes')) }}
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $formTipoGasto }}</td>
            <td></td>
            <td></td>
            <td>
                <input type="text" name="monto" class="form-control form-control-sm {{ $errors->has('monto') ? 'is-invalid' : '' }}">
            </td>
            <td>
                <button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button>
            </td>
        </tr>
    </tbody>
</table>
{{ Form::close() }}
@endif
@endsection
