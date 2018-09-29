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

<table class="offset-md-1 col-md-10 mt-md-3 table table-hover table-sm">
    <thead class="thead-light">
        <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Fecha</th>
            <th>Glosa</th>
            <th>Serie</th>
            <th>Tipo Gasto</th>
            <th>Mov</th>
            <th class="text-right">Monto</th>
            <th class="text-right">Saldo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $saldo = $saldoMesAnterior; ?>
        <tr>
            <th>{{ request()->input('anno') }}</th>
            <th>{{ request()->input('mes') }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th>Saldo Inicial</th>
            <th></th>
            <th class="text-right"></th>
            <th class="text-right">$ {{ number_format($saldo, 0, ',', '.') }}</th>
            <th></th>
        </tr>
    @foreach ($movimientosMes as $mov)
        <tr>
            <td>{{ $mov->anno }}</td>
            <td>{{ $mov->mes }}</td>
            <td>{{ isset($mov->fecha) ? $mov->fecha->format('d-m-Y') : null }}</td>
            <td>{{ $mov->glosa }}</td>
            <td>{{ $mov->serie }}</td>
            <td>{{ $mov->tipoGasto->tipo_gasto }}</td>
            <td>{{ $mov->tipoMovimiento->tipo_movimiento }}</td>
            <td class="text-right">
                $&nbsp;{{ number_format($mov->monto, 0, ',', '.') }}
                @if ($mov->tipoMovimiento->signo == -1)
                    <small><span class="fa fa-minus-circle text-danger"></span></small>
                @else
                    <small><span class="fa fa-plus-circle text-success"></span></small>
                @endif
            </td>
            <td class="text-right">
                $&nbsp;{{ number_format($saldo += $mov->tipoMovimiento->signo*$mov->monto, 0, ',', '.') }}
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
        {{ Form::open([]) }}
        <tr>
            {{ Form::hidden('cuenta_id', request()->input('cuenta_id')) }}
            {{ Form::hidden('anno', request()->input('anno')) }}
            {{ Form::hidden('mes', request()->input('mes')) }}
            <td></td>
            <td></td>
            <td>
                {{ Form::date('fecha', request()->input('fecha'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm']) }}
            </td>
            <td>
                {{ Form::text('glosa', request()->input('glosa'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm']) }}
            </td>
            <td>
                {{ Form::text('serie', request()->input('serie'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm']) }}
            </td>
            <td>{{ $formTipoGasto }}</td>
            <td></td>
            <td>
                <input type="text" name="monto" autocomplete="off" class="form-control form-control-sm {{ $errors->has('monto') ? 'is-invalid' : '' }}">
            </td>
            <td>
                <button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button>
            </td>
            <td></td>
        </tr>
        {{ Form::close() }}
    </tbody>
</table>

@endsection
