@extends('common.app_layout')

@section('modulo')
<form>
    <div class="form-row">
        <div class="offset-md-3 col-md-3">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">Año</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-3 col-md-3"> {{ $formCuenta }} </div>
        <div class="col-md-2"> {{ $formAnno }} </div>
        <div class="col-md-4">
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
            <th>Tipo Movimiento</th>
            <th class="text-right">Monto</th>
            <th class="text-right">Saldo</th>
            <th></th>
            <th class="text-right">Util</th>
            <th class="text-right">Rentab</th>
            <th class="text-right">Rentab Año</th>
        </tr>
    </thead>
    <tbody>
    <?php $saldo = 0; ?>
    @foreach ($inversion->getMovimientos() as $mov)
        <tr>
            <td>{{ $mov->anno }}</td>
            <td>{{ $mov->mes }}</td>
            <td>{{ optional($mov->fecha)->format('d-m-Y') }}</td>
            <td>{{ $mov->glosa }}</td>
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
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
        <tr class="bg-light">
            <th>{{ optional($inversion->saldoFinal())->anno }}</th>
            <th>{{ optional($inversion->saldoFinal())->mes }}</th>
            <th>{{ optional(optional($inversion->saldoFinal())->fecha)->format('d-m-Y') }}</th>
            <th>{{ optional($inversion->saldoFinal())->glosa }}</th>
            <th>{{ optional(optional($inversion->saldoFinal())->tipoMovimiento)->tipo_movimiento }}</th>
            <th></th>
            <th class="text-right">
                $&nbsp;{{ number_format(optional($inversion->saldoFinal())->monto, 0, ',', '.') }}
            </th>
            <th></th>
            <th class="text-right">
                $&nbsp;{{ number_format($inversion->util(), 0, ',', '.') }}
            </th>
            <th class="text-right">
                {{ number_format(100*$inversion->rentabilidad(), 2, ',', '.') }}%
            </th>
            <th class="text-right">
                {{ number_format(100*$inversion->rentabilidadAnual(), 2, ',', '.') }}%
            </th>
        </tr>
        {{ Form::open([]) }}
        <tr>
            {{ Form::hidden('cuenta_id', request()->input('cuenta_id')) }}
            {{ Form::hidden('anno', request()->input('anno')) }}
            <td></td>
            <td></td>
            <td>
                {{ Form::date('fecha', request()->input('fecha'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm'.($errors->has('fecha') ? ' is-invalid' : '')]) }}
            </td>
            <td>
                {{ Form::text('glosa', request()->input('glosa'), ['autocomplete' => 'off', 'class' => 'form-control form-control-sm'.($errors->has('glosa') ? ' is-invalid' : '')]) }}
            </td>
            <td>{{ $formTipoMovimiento }}</td>
            <td>
                <input type="text" name="monto" autocomplete="off" class="form-control form-control-sm {{ $errors->has('monto') ? 'is-invalid' : '' }}">
            </td>
            <td>
                <button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        {{ Form::close() }}
    </tbody>
</table>

@endsection
