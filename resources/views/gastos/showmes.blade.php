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
        <div class="offset-md-2 col-md-2">
            {{ Form::select('cuenta_id', $formCuenta, request('cuenta_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::select('anno', $formAnno, request('anno', $annoDefault), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::select('mes', $formMes, request('mes', $mesDefault), ['class' => 'form-control']) }}
         </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Consultar</button>
            <button name="recalcula" value="recalcula" class="btn btn-secondary pull-right">Recalcula saldos</button>
        </div>
    </div>
</form>

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
            <th class="text-right">Saldo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {{ Form::open([]) }}
        <tr>
            {{ Form::hidden('cuenta_id', request('cuenta_id')) }}
            {{ Form::hidden('anno', request('anno')) }}
            {{ Form::hidden('mes', request('mes')) }}
            <td></td>
            <td></td>
            <td>
                {{ Form::date('fecha', request('fecha'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm'.($errors->has('fecha') ? ' is-invalid' : '')]) }}
            </td>
            <td>
                {{ Form::text('glosa', request('glosa'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm']) }}
            </td>
            <td>
                {{ Form::text('serie', request('serie'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm']) }}
            </td>
            <td>
                {{ Form::select('tipo_gasto_id', $formTipoGasto, request('tipo_gasto_id'), ['class' => 'form-control form-control-sm']) }}
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

        <?php $saldo = $saldoMesAnterior + $movimientosMes->map(function($gasto) {return $gasto->monto * $gasto->tipoMovimiento->signo;})->sum(); ?>
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
            <td class="text-right">
                $&nbsp;{{ number_format($saldo, 0, ',', '.') }}
                <?php $saldo -= $mov->monto * $mov->tipoMovimiento->signo; ?>
            </td>
            <td>
                {{ Form::open(['url' => route('gastos.borrarGasto', http_build_query(request()->all()))]) }}
                    {!! method_field('DELETE')!!}
                    {{ Form::hidden('id', $mov->getKey()) }}
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
            <th class="text-right"></th>
            <th class="text-right">$ {{ number_format($saldo, 0, ',', '.') }}</th>
            <th></th>
        </tr>
    </tbody>
</table>

@endsection
